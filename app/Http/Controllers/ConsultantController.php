<?php

namespace App\Http\Controllers;

use App\Models\Consultant;
use App\Models\State;
use App\Models\City;
use App\Models\College;
use App\Models\ConsultantKyc;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class ConsultantController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 🔐 Consultant can only see their own record
        if ($user->hasRole('consultant')) {
            $consultant = $user->consultant; // Assuming User hasOne Consultant
            
            if (!$consultant) {
                // No consultant profile linked? Show empty or redirect
                return view('pages.consultants.index', [
                    'consultants' => collect(),
                    'states' => collect(),
                    'cities' => collect(),
                    'activeColleges' => collect()
                ])->with('error', 'No consultant profile found. Please contact admin.');
            }

            // Load only their own data
            $consultants = collect([$consultant->load([
                'state',
                'city',
                'kycDocuments' => function ($query) {
                    $query->whereNull('is_verified')->orderBy('created_at', 'desc');
                }
            ])]);

            $states = State::where('id', $consultant->state_id)->get();
            $cities = City::where('id', $consultant->city_id)->get();
            $activeColleges = $consultant->lockedColleges()
                ->where('colleges.status', 'active')
                ->select('colleges.id', 'colleges.name')
                ->orderBy('colleges.name')
                ->get();

            return view('pages.consultants.index', compact('consultants', 'states', 'cities', 'activeColleges'));
        }

        // ✅ Admin/Superadmin: See all consultants
        $consultants = Consultant::with([
            'state',
            'city',
            'kycDocuments' => function ($query) {
                $query->whereNull('is_verified')->orderBy('created_at', 'desc');
            }
        ])->orderBy('created_at', 'desc')->get();

        $states = State::orderBy('name')->get();
        $cities = City::orderBy('name')->get();
        $activeColleges = College::where('status', 'active')
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        return view('pages.consultants.index', compact('consultants', 'states', 'cities', 'activeColleges'));
    }

    public function show($id)
    {
        $user = Auth::user();

        // 🔐 Consultant can only view their own profile
        if ($user->hasRole('consultant')) {
            $consultant = $user->consultant;
            
            if (!$consultant || $consultant->id != $id) {
                abort(403, 'Unauthorized access to this consultant profile.');
            }
        }

        $consultant = Consultant::with([
            'state',
            'city',
            'kycDocuments' => function ($query) {
                $query->orderBy('created_at', 'desc');
            }
        ])->findOrFail($id);

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
        // 🔐 Only admin/staff can create consultants
        if (Auth::user()->hasRole('consultant')) {
            abort(403, 'Consultants cannot create new consultant records.');
        }

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
        $user = Auth::user();
        $consultant = Consultant::findOrFail($id);

        // 🔐 Consultant can only update their own profile
        if ($user->hasRole('consultant')) {
            if ($user->consultant?->id !== $consultant->id) {
                abort(403, 'You can only update your own profile.');
            }
            // Optional: Restrict which fields consultant can update
            $validated = $request->validate([
                'name' => 'required|string|max:100',
                'phone' => 'required|string|max:20',
                'address' => 'required|string',
                // ❌ Consultants cannot change email, state, city, status
            ]);
            $consultant->update($validated);
            return redirect()->back()->with('success', 'Profile updated successfully!');
        }

        // ✅ Admin: Full update
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => ['required', 'email', Rule::unique('consultants', 'email')->ignore($id)],
            'phone' => 'required|string|max:20',
            'state' => 'required|exists:states,id',
            'city' => 'required|exists:cities,id',
            'address' => 'required|string',
            'status' => 'required|in:0,1',
        ]);

        $consultant->update($request->all());
        return redirect()->back()->with('success', 'Consultant updated successfully!');
    }

    public function destroy(string $id)
    {
        // 🔐 Consultants cannot delete any consultant (including themselves)
        if (Auth::user()->hasRole('consultant')) {
            abort(403, 'Consultants cannot delete records.');
        }

        $consultant = Consultant::findOrFail($id);
        $consultant->delete();
        return redirect()->back()->with('success', 'Consultant deleted successfully!');
    }

    // API Endpoint for Dependent Dropdown
    public function getCitiesByState($stateId)
    {
        $cities = City::where('state_id', $stateId)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($cities);
    }

    public function uploadKyc(Request $request, $id)
    {
        $user = Auth::user();
        $consultant = Consultant::findOrFail($id);

        // 🔐 Consultant can only upload KYC for their own profile
        if ($user->hasRole('consultant')) {
            if ($user->consultant?->id !== $consultant->id) {
                abort(403, 'You can only upload documents for your own profile.');
            }
        }

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
        // 🔐 Only admin/staff can verify KYC
        if (Auth::user()->hasRole('consultant')) {
            abort(403, 'Consultants cannot verify KYC documents.');
        }

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
        // 🔐 Only admin/staff can reject KYC
        if (Auth::user()->hasRole('consultant')) {
            abort(403, 'Consultants cannot reject KYC documents.');
        }

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

    public function lockCollege(Request $request, $consultantId)
    {
        $user = Auth::user();
        $consultant = Consultant::findOrFail($consultantId);

        // 🔐 Consultant can only lock colleges for their own profile
        if ($user->hasRole('consultant')) {
            if ($user->consultant?->id !== $consultant->id) {
                abort(403, 'You can only manage colleges for your own profile.');
            }
        }

        $request->validate([
            'college_id' => 'required|exists:colleges,id'
        ]);
        
        if ($consultant->lockedColleges()->where('college_id', $request->college_id)->exists()) {
            return back()->with('error', 'This college is already locked!');
        }
        
        $consultant->lockedColleges()->attach($request->college_id, [
            'locked_by' => Auth::id()
        ]);
        
        return back()->with('success', 'College locked successfully!');
    }

    public function unlockCollege($consultantId, $collegeId)
    {
        $user = Auth::user();
        $consultant = Consultant::findOrFail($consultantId);

        // 🔐 Consultant can only unlock colleges for their own profile
        if ($user->hasRole('consultant')) {
            if ($user->consultant?->id !== $consultant->id) {
                abort(403, 'You can only manage colleges for your own profile.');
            }
        }

        $consultant->lockedColleges()->detach($collegeId);
        return back()->with('success', 'College unlocked!');
    }

    // Helper method
    protected function formatFileSize($bytes)
    {
        if ($bytes >= 1073741824) return round($bytes / 1073741824, 2) . ' GB';
        if ($bytes >= 1048576) return round($bytes / 1048576, 2) . ' MB';
        if ($bytes >= 1024) return round($bytes / 1024, 2) . ' KB';
        return $bytes . ' B';
    }
}