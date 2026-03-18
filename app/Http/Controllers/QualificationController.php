<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Qualification;

class QualificationController extends Controller
{
     public function index()
    {
        $qualifications = Qualification::orderBy('created_at', 'desc')->paginate(10);
        return view('pages.qualifications.index', compact('qualifications'));
    }

    /**
     * Store a newly created reQualification in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'status' => 'required|in:0,1',
        ]);

        Qualification::create([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Qualification created successfully!');
    }

    /**
     * Update the specified reQualification in storage.
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

        $qualification = Qualification::findOrFail($id);
        $qualification->update([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Qualification updated successfully!');
    }

    /**
     * Remove the specified reQualification from storage.
     */
    public function destroy(string $id)
    {
        $qualification = Qualification::findOrFail($id);
        $qualification->delete();

        return redirect()->back()->with('success', 'Qualification deleted successfully!');
    }
}
