<?php

namespace App\Http\Controllers;

use App\Models\College;
use App\Models\State;
use App\Models\City;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;


class CollegeController extends Controller
{
    public function index()
    {
        $colleges = College::with(['state', 'city'])->latest()->paginate(10);
        $states = State::all();
        $courses = Course::all();
        
        return view('pages.colleges.index', compact('colleges', 'states', 'courses'));
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