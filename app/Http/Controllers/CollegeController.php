<?php

namespace App\Http\Controllers;

use App\Models\College;
use App\Models\State;
use App\Models\City;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CollegesExport;

class CollegeController extends Controller
{
    public function index(Request $request)
    {
        $query = College::with(['state', 'city']);

        // 🔍 Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // 🏷️ Status Filter
        if ($request->filled('status') && in_array($request->status, ['active', 'inactive'])) {
            $query->where('status', $request->status);
        }

        // 🌍 Location Filters
        if ($request->filled('state_id')) {
            $query->where('state_id', $request->state_id);
        }
        if ($request->filled('city_id')) {
            $query->where('city_id', $request->city_id);
        }

        // ⭐ Rating Filter (if you have a rating column)
        if ($request->filled('min_rating')) {
            $query->where('rating', '>=', $request->min_rating);
        }

        // 🏷️ Tags Filter (if stored as JSON/array)
        if ($request->filled('tags')) {
            $tags = array_filter(explode(',', $request->tags));
            foreach ($tags as $tag) {
                $query->whereJsonContains('tags', trim($tag));
            }
        }

        // 👥 Owner Filter (if you have user_id/owner_id)
        if ($request->filled('owner_id')) {
            $query->where('user_id', $request->owner_id);
        }

        // 📚 Course Filter (JSON column)
        if ($request->filled('course_id')) {
            $query->whereJsonContains('course_ids', (int)$request->course_id);
        }

        $colleges = $query->latest()->paginate(10)->withQueryString(); // ✅ Keeps filters in pagination
        $states = State::all();
        $courses = Course::all();
        
        return view('pages.colleges.index', compact('colleges', 'states', 'courses'));
    }
     public function exportPdf(Request $request)
    {
        $query = College::with(['state', 'city']);
        
        // Apply same filters as index()
        $this->applyFilters($query, $request);
        
        $colleges = $query->get();
        $pdf = Pdf::loadView('pages.colleges.exports.pdf', compact('colleges'));
        return $pdf->download('colleges-' . date('Y-m-d') . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        $query = College::with(['state', 'city']);
        $this->applyFilters($query, $request);
        
        return Excel::download(new CollegesExport($query->get()), 'colleges-' . date('Y-m-d') . '.xlsx');
    }

    // Reusable filter method
    private function applyFilters($query, Request $request)
    {
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        if ($request->filled('status') && in_array($request->status, ['active', 'inactive'])) {
            $query->where('status', $request->status);
        }
        if ($request->filled('state_id')) {
            $query->where('state_id', $request->state_id);
        }
        if ($request->filled('city_id')) {
            $query->where('city_id', $request->city_id);
        }
        if ($request->filled('min_rating')) {
            $query->where('rating', '>=', $request->min_rating);
        }
        if ($request->filled('tags')) {
            $tags = array_filter(explode(',', $request->tags));
            foreach ($tags as $tag) {
                $query->whereJsonContains('tags', trim($tag));
            }
        }
        if ($request->filled('owner_id')) {
            $query->where('user_id', $request->owner_id);
        }
        if ($request->filled('course_id')) {
            $query->whereJsonContains('course_ids', (int)$request->course_id);
        }
    }


   public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'state_id' => 'required|exists:states,id',
        'city_id' => 'required|exists:cities,id',
        'email' => 'required|email',
        'college_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:800',
        'course_ids' => 'nullable|array',
        'course_ids.*' => 'exists:courses,id',
        'status' => 'nullable|in:active,inactive',
    ]);

    $data = $request->except(['college_image', 'course_ids']);
    $data['status'] = $request->get('status', 'active');

    // Handle image upload
    if ($request->hasFile('college_image')) {
        $data['college_image'] = $request->file('college_image')->store('colleges', 'public');
    }

    // ✅ Handle course_ids - Debug this
    Log::info('Course IDs received:', ['course_ids' => $request->course_ids]);
    
    if ($request->has('course_ids') && is_array($request->course_ids)) {
        $data['course_ids'] = $request->course_ids;
        Log::info('Course IDs added to data:', ['data' => $data['course_ids']]);
    } else {
    Log::info('No course_ids in request or not an array');
    }

    $college = College::create($data);
    
    Log::info('College created:', ['id' => $college->id, 'course_ids' => $college->course_ids]);

    return redirect()->route('colleges.index')->with('success', 'College added successfully!');
}
    public function update(Request $request, College $college)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'state_id' => 'required|exists:states,id',
            'city_id' => 'required|exists:cities,id',
            'email' => 'required|email',
            'college_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:800',
            'course_ids' => 'nullable|array',
            'course_ids.*' => 'exists:courses,id',
            'status' => 'nullable|in:active,inactive',
        ]);

        $data = $request->except(['college_image', 'course_ids']);

        if ($request->has('status')) {
            $data['status'] = $request->status;
        }

        // Handle image upload
        if ($request->hasFile('college_image')) {
            if ($college->college_image) {
                Storage::disk('public')->delete($college->college_image);
            }
            $data['college_image'] = $request->file('college_image')->store('colleges', 'public');
        }

        // Handle course_ids (store as JSON via $casts)
        if ($request->has('course_ids')) {
            $data['course_ids'] = $request->course_ids;
        }

        $college->update($data);

        return redirect()->route('colleges.index')->with('success', 'College updated successfully!');
    }

    public function destroy(College $college)
    {
        if ($college->college_image) {
            Storage::disk('public')->delete($college->college_image);
        }
        $college->delete();

        return redirect()->route('colleges.index')->with('success', 'College deleted successfully!');
    }

    public function getCities($stateId)
    {
        $cities = City::where('state_id', $stateId)->get(['id', 'name']);
        return response()->json($cities);
    }
}