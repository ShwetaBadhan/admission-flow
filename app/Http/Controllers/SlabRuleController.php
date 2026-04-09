<?php

namespace App\Http\Controllers;

use App\Models\SlabRule;
use App\Models\Consultant;
use App\Models\College;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SlabRuleController extends Controller
{
   public function index(Request $request)
{
    $query = SlabRule::with(['consultant', 'college']);

    // 🔍 1. Search (Consultant, College, Course)
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->whereHas('consultant', fn($sub) => $sub->where('name', 'like', "%{$search}%"))
              ->orWhereHas('college', fn($sub) => $sub->where('name', 'like', "%{$search}%"))
              ->orWhere('course_name', 'like', "%{$search}%");
        });
    }

    // 📊 2. Status Filter
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    // 🎯 3. Threshold Filter (Multiple values: 5, 10, 15+)
    if ($request->filled('threshold')) {
        $thresholds = is_array($request->threshold) ? $request->threshold : [$request->threshold];
        $exact = array_filter($thresholds, fn($t) => $t !== '15+');
        $has15Plus = in_array('15+', $thresholds);

        if (!empty($exact) && $has15Plus) {
            $query->whereIn('threshold', $exact)->orWhere('threshold', '>=', 15);
        } elseif (!empty($exact)) {
            $query->whereIn('threshold', $exact);
        } elseif ($has15Plus) {
            $query->where('threshold', '>=', 15);
        }
    }

    // 📅 4. Date Range
    if ($request->filled('start_date') && $request->filled('end_date')) {
        $query->whereBetween('created_at', [
            $request->start_date . ' 00:00:00',
            $request->end_date . ' 23:59:59'
        ]);
    }

    // ⬆️⬇️ 5. Sorting
    $sort = $request->get('sort', 'threshold_asc');
    match ($sort) {
        'threshold_desc' => $query->orderBy('threshold', 'desc'),
        'newest' => $query->orderBy('created_at', 'desc'),
        default => $query->orderBy('threshold', 'asc')
    };

    // 📄 Pagination with preserved query params
    $slabRules = $query->paginate(15)->withQueryString();
    
    $consultants = Consultant::where('status', 1)->orderBy('name')->get();
    $colleges = College::where('status', 1)->orderBy('name')->get();
    $courses = Course::where('status', 1)->orderBy('name')->pluck('name')->unique();

    return view('pages.slab-rules.index', compact('slabRules', 'consultants', 'colleges', 'courses'));
}

    public function store(Request $request)
    {
        $validated = $request->validate([
            'consultant_id' => 'nullable|exists:consultants,id',
            'college_id' => 'nullable|exists:colleges,id',
            'course_name' => 'nullable|string|max:255',
            'threshold' => 'required|integer|min:1',
            'bonus_type' => 'required|in:fixed_amount,percentage_of_commission,percentage_of_fee',
            'bonus_value' => 'required|numeric|min:0',
            'currency' => 'required|string|max:3',
            'scope' => 'required|in:per_college,global',
            'retroactive' => 'nullable|boolean',
            'status' => 'required|in:active,inactive',
            'notes' => 'nullable|string',
        ]);

        // Prevent exact duplicates
        $exists = SlabRule::where('consultant_id', $validated['consultant_id'] ?? null)
            ->where('college_id', $validated['college_id'] ?? null)
            ->where('course_name', $validated['course_name'] ?? null)
            ->where('threshold', $validated['threshold'])
            ->exists();

        if ($exists) {
            return back()->withInput()->with('error', 'A slab rule with this exact configuration already exists.');
        }

        $validated['retroactive'] = $request->has('retroactive');
        SlabRule::create($validated);
        return redirect()->route('slab-rules.index')->with('success', 'Slab rule created successfully.');
    }

    public function update(Request $request, SlabRule $slabRule)
    {
        $validated = $request->validate([
            'consultant_id' => 'nullable|exists:consultants,id',
            'college_id' => 'nullable|exists:colleges,id',
            'course_name' => 'nullable|string|max:255',
            'threshold' => 'required|integer|min:1',
            'bonus_type' => 'required|in:fixed_amount,percentage_of_commission,percentage_of_fee',
            'bonus_value' => 'required|numeric|min:0',
            'currency' => 'required|string|max:3',
            'scope' => 'required|in:per_college,global',
            'retroactive' => 'nullable|boolean',
            'status' => 'required|in:active,inactive',
            'notes' => 'nullable|string',
        ]);

        $validated['retroactive'] = $request->has('retroactive');
        $slabRule->update($validated);
        return redirect()->route('slab-rules.index')->with('success', 'Slab rule updated successfully.');
    }

    public function destroy(SlabRule $slabRule)
    {
        $slabRule->delete();
        return redirect()->route('slab-rules.index')->with('success', 'Slab rule deleted successfully.');
    }
}
