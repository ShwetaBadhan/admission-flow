<?php

namespace App\Http\Controllers;

use App\Models\Consultant;
use App\Models\State;
use App\Models\City;
use App\Models\ConsultantKyc;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;


class ConsultantController extends Controller
{
    public function index()
    {
        // ✅ Eager load state and city relationships
        $consultants = Consultant::with([
            'state',
            'city',
            'kycDocuments' => function ($query) {
                $query->whereNull('is_verified')->orderBy('created_at', 'desc'); // Only pending
            }
        ])->orderBy('created_at', 'desc')->paginate(10);
        $states = State::orderBy('name')->get();
        $cities = City::orderBy('name')->get(); // Load all cities for initial edit modals


        return view('pages.consultants.index', compact('consultants', 'states', 'cities'));
    }

    public function show($id)
    {
        $consultant = Consultant::with([
            'state',
            'city',
            'kycDocuments' => function ($query) {
                $query->orderBy('created_at', 'desc');
            }
        ])->findOrFail($id);

        // Summary stats
        $stats = [
            'total_docs' => $consultant->kycDocuments->count(),
            'pending' => $consultant->kycDocuments->where('is_verified', null)->count(),
            'verified' => $consultant->kycDocuments->where('is_verified', true)->count(),
            'rejected' => $consultant->kycDocuments->where('is_verified', false)->count(),
        ];

        return view('pages.consultants.show', compact('consultant', 'stats'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:consultants,email',
            'phone' => 'required|string|max:20',
            'state' => 'required|exists:states,id',
            'city' => 'required|exists:cities,id',
            'address' => 'required|string',
            'status' => 'required|in:0,1',
        ]);

        Consultant::create($request->all());
        return redirect()->back()->with('success', 'Consultant created successfully!');
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => ['required', 'email', Rule::unique('consultants', 'email')->ignore($id)],
            'phone' => 'required|string|max:20',
            'state' => 'required|exists:states,id',
            'city' => 'required|exists:cities,id',
            'address' => 'required|string',
            'status' => 'required|in:0,1',
        ]);

        $consultant = Consultant::findOrFail($id);
        $consultant->update($request->all());
        return redirect()->back()->with('success', 'Consultant updated successfully!');
    }

    public function destroy(string $id)
    {
        $consultant = Consultant::findOrFail($id);
        $consultant->delete();
        return redirect()->back()->with('success', 'Consultant deleted successfully!');
    }



    // API Endpoint for Dependent Dropdown
    public function getCitiesByState($stateId)
    {
        $cities = City::where('state_id', $stateId)
            ->orderBy('name')
            ->get(['id', 'name']); // Only fetch needed columns

        return response()->json($cities);
    }

    // ConsultantController.php

    public function uploadKyc(Request $request, $id)
    {
        $consultant = Consultant::findOrFail($id);

        $validated = $request->validate([
            'document_type' => [
                'required',
                'string',
                Rule::unique('consultant_kyc', 'document_type')
                    ->where('consultant_id', $consultant->id)
            ],
            'document_number' => 'nullable|string|max:100',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png,webp|max:10240',
            'remarks' => 'nullable|string|max:500'
        ], [
            'document_type.unique' => 'This document type is already uploaded for this consultant.'
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store('consultants/' . $consultant->id . '/kyc', 'public');

            $consultant->kycDocuments()->create([
                'document_type' => $validated['document_type'],
                'document_number' => $validated['document_number'] ?? null,
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'file_size' => $this->formatFileSize($file->getSize()),
                'uploaded_by' => Auth::id() ?? 'System',
                'remarks' => $validated['remarks'] ?? null,
                'is_verified' => null
            ]);
        }

        return back()->with('success', 'KYC document uploaded successfully!');
    }

    public function verifyKyc($id, $kyc_id)
    {
        // Ensure KYC belongs to this consultant (security)
        $kyc = ConsultantKyc::where('id', $kyc_id)
            ->where('consultant_id', $id)
            ->firstOrFail();

        $kyc->update([
            'is_verified' => true,
            'verified_at' => now(),
            'verified_by' => Auth::id() ?? 'Admin'
        ]);

        return back()->with('success', 'KYC document verified successfully!');
    }

    public function rejectKyc($id, $kyc_id)
    {
        $kyc = ConsultantKyc::where('id', $kyc_id)
            ->where('consultant_id', $id)
            ->firstOrFail();

        $kyc->update([
            'is_verified' => false,
            'verified_at' => now(),
            'verified_by' => Auth::id() ?? 'Admin'
        ]);

        return back()->with('info', 'KYC document rejected.');
    }
    // Helper method (if not already present)
    protected function formatFileSize($bytes)
    {
        if ($bytes >= 1073741824) return round($bytes / 1073741824, 2) . ' GB';
        if ($bytes >= 1048576) return round($bytes / 1048576, 2) . ' MB';
        if ($bytes >= 1024) return round($bytes / 1024, 2) . ' KB';
        return $bytes . ' B';
    }
}
