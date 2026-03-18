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
     * Display a listing of the resource.
     */
    public function index()
    {
        $commissionRules = CommissionRule::with(['consultant', 'college'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Fetch dropdown data (only active records)
        $consultants = Consultant::where('status', 1)->orderBy('name')->get();
        $colleges = College::where('status', 1)->orderBy('name')->get();
        $courses = Course::where('status', 1)->orderBy('name')->get();

        return view('pages.commission-rules.index', compact(
            'commissionRules',
            'consultants',
            'colleges',
            'courses'
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
        $validated = $request->validate([
            'consultant_id' => 'required|exists:consultants,id',
            'college_id' => 'nullable|exists:colleges,id',
            'course_name' => 'required|string|max:255',
            'commission_type' => 'required|in:fixed_amount,percentage',
             'commission_value' => [
        'required', 
        'numeric', 
        'min:0',
        // Custom rule: percentage cannot exceed 100
        function ($attribute, $value, $fail) use ($request) {
            if ($request->commission_type === 'percentage' && $value > 100) {
                $fail('Percentage value cannot exceed 100%.');
            }
        }
    ],
            'currency' => 'required|string|max:3',
            'status' => 'required|in:active,inactive',
            'notes' => 'nullable|string',
            
        ]);
        // Convert empty string to null for nullable foreign keys
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
    $commissionRule = CommissionRule::findOrFail($id);

    $validated = $request->validate([
        'consultant_id' => 'required|exists:consultants,id',
        'college_id' => 'nullable|exists:colleges,id',
        'course_name' => 'required|string|max:255',
        'commission_type' => 'required|in:fixed_amount,percentage',
        'commission_value' => 'required|numeric|min:0',
        'currency' => 'required|string|max:3',
        'status' => 'required|in:active,inactive',
        'notes' => 'nullable|string',
    ]);

    // ✅ Add this line to convert empty string to null
    $validated['college_id'] = $validated['college_id'] ?: null;

    $commissionRule->update($validated);

    return redirect()->route('commission-rules.index')
        ->with('success', 'Commission rule updated successfully.');
}

    /**
     * Remove the specified resource from storage.
     */public function destroy(string $id)
{
    $commissionRule = CommissionRule::findOrFail($id);
    $commissionRule->delete();  // ✅ Correct

    return redirect()->route('commission-rules.index')
        ->with('success', 'Commission rule deleted successfully.');
}
}
