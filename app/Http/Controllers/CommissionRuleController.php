<?php

namespace App\Http\Controllers;

use App\Models\CommissionRule;
use App\Models\Consultant;
use App\Models\College;
use App\Models\Course; // Assuming you have a Course model
use Illuminate\Http\Request;

class CommissionRuleController extends Controller
{
    /**
 * Helper: Get authenticated consultant record (if user is consultant)
 */
private function getAuthenticatedConsultant()
{
    $user = auth()->user();
    
    if (!$user) return null;

    // ✅ Role check (adjust based on your auth system)
    if (method_exists($user, 'hasRole') && !$user->hasRole('consultant')) {
        return null;
    }
    
    if (isset($user->role) && $user->role !== 'consultant') {
        return null;
    }

    // ✅ Fetch consultant record (assuming user.id = consultant.id)
    return $user->consultant ?? \App\Models\Consultant::where('id', $user->id)->first();
}
    /**
     * Display a listing of the resource.
     */
 public function index()
{
    $query = CommissionRule::with(['consultant', 'college']);
    
    // 🔐 Consultant? Only show THEIR rules
    $consultant = $this->getAuthenticatedConsultant();
    
    if ($consultant) {
        // Consultant hai → sirf unki rules dikhao
        $query->where('consultant_id', $consultant->id);
    }
    // ✅ Admin/Superadmin? Sab rules dikhengi (no filter)
    
    $commissionRules = $query->orderBy('created_at', 'desc')->paginate(10);

    // Dropdown data (consultant ke liye bhi safe)
    $consultants = Consultant::where('status', 1)->orderBy('name')->get();
    $colleges = College::where('status', 1)->orderBy('name')->get();
    $courses = Course::where('status', 1)->orderBy('name')->get();

    return view('pages.commission-rules.index', compact(
        'commissionRules', 'consultants', 'colleges', 'courses'
    ));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Fetch only active consultants
        $consultants = Consultant::where('status', 'active')
            ->orderBy('name')
            ->get();

        // Fetch only active colleges
        $colleges = College::where('status', 'active')
            ->orderBy('name')
            ->get();

        // Fetch only active courses
        $courses = Course::where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('commission-rules.create', compact('consultants', 'colleges', 'courses'));
    }

    /**
     * Store a newly created resource in storage.
     */
public function store(Request $request)
{
    $consultant = $this->getAuthenticatedConsultant();
    
    // 🔐 Validation rules - conditional based on user role
    $validationRules = [
        'college_id' => 'nullable|exists:colleges,id',
        'course_name' => 'required|string|max:255',
        'commission_type' => 'required|in:fixed_amount,percentage',
        'commission_value' => [
            'required', 'numeric', 'min:0',
            function ($attribute, $value, $fail) use ($request) {
                if ($request->commission_type === 'percentage' && $value > 100) {
                    $fail('Percentage value cannot exceed 100%.');
                }
            }
        ],
        'currency' => 'required|string|max:3',
        'status' => 'required|in:active,inactive',
        'notes' => 'nullable|string',
    ];
    
    // ✅ Admin MUST select consultant_id, Consultant gets it auto-assigned
    if ($consultant) {
        // Consultant: auto-assign, ignore request input
        $validated = $request->validate($validationRules);
        $validated['consultant_id'] = $consultant->id;
        $validated['status'] = 'active'; // Optional: consultants can't create inactive
    } else {
        // Admin: consultant_id is required from form
        $validationRules['consultant_id'] = 'required|exists:consultants,id';
        $validated = $request->validate($validationRules);
    }
    
    $validated['college_id'] = $validated['college_id'] ?: null;
    
    CommissionRule::create($validated);

    return redirect()->route('commission-rules.index')
        ->with('success', 'Commission rule created successfully.');
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $commissionRule = CommissionRule::with(['consultant', 'college'])->findOrFail($id);
        return view('commission-rules.show', compact('commissionRule'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $commissionRule = CommissionRule::findOrFail($id);

        // Fetch only active consultants
        $consultants = Consultant::where('status', 'active')
            ->orderBy('name')
            ->get();

        // Fetch only active colleges
        $colleges = College::where('status', 'active')
            ->orderBy('name')
            ->get();

        // Fetch only active courses
        $courses = Course::where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('pages.commission-rules.edit', compact('commissionRule', 'consultants', 'colleges', 'courses'));
    }

    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, string $id)
{
    $consultant = $this->getAuthenticatedConsultant();
    $commissionRule = CommissionRule::findOrFail($id);

    // 🔐 Consultant can only edit THEIR rules
    if ($consultant && $commissionRule->consultant_id !== $consultant->id) {
        abort(403, 'Unauthorized action.');
    }

    $validated = $request->validate([
        'college_id' => 'nullable|exists:colleges,id',
        'course_name' => 'required|string|max:255',
        'commission_type' => 'required|in:fixed_amount,percentage',
        'commission_value' => 'required|numeric|min:0',
        'currency' => 'required|string|max:3',
        'status' => 'required|in:active,inactive',
        'notes' => 'nullable|string',
    ]);

    // Consultant can't change consultant_id or set status to inactive (optional)
    if ($consultant) {
        unset($validated['consultant_id']); // Prevent tampering
        // Optional: $validated['status'] = 'active';
    }

    $validated['college_id'] = $validated['college_id'] ?: null;
    
    $commissionRule->update($validated);

    return redirect()->route('commission-rules.index')
        ->with('success', 'Commission rule updated successfully.');
}
    /**
     * Remove the specified resource from storage.
     */
public function destroy(string $id)
{
    $consultant = $this->getAuthenticatedConsultant();
    $commissionRule = CommissionRule::findOrFail($id);

    // 🔐 Consultant can only delete THEIR rules
    if ($consultant && $commissionRule->consultant_id !== $consultant->id) {
        abort(403, 'Unauthorized action.');
    }

    $commissionRule->delete();

    return redirect()->route('commission-rules.index')
        ->with('success', 'Commission rule deleted successfully.');
}

}
