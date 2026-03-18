<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;

class CoursesController extends Controller
{
    /**
     * Display a listing of the reCourse.
     */
   public function index()
    {
        $courses = course::orderBy('created_at', 'desc')->paginate(10);
        return view('pages.courses.index', compact('courses'));
    }

    /**
     * Store a newly created reCourse in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'status' => 'required|in:0,1',
        ]);

        course::create([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Course created successfully!');
    }

    /**
     * Update the specified reCourse in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
            ],
            'status' => 'required|in:0,1',
        ]);

        $course = course::findOrFail($id);
        $course->update([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Course updated successfully!');
    }

    /**
     * Remove the specified reCourse from storage.
     */
    public function destroy(string $id)
    {
        $course = course::findOrFail($id);
        $course->delete();

        return redirect()->back()->with('success', 'Course deleted successfully!');
    }
}
