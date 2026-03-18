<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Priority;

class PriorityController extends Controller
{
     public function index()
    {
        $priorities = Priority::orderBy('created_at', 'desc')->paginate(10);
        return view('pages.priorities.index', compact('priorities'));
    }

    /**
     * Store a newly created rePriority in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'status' => 'required|in:0,1',
        ]);

        Priority::create([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Priority created successfully!');
    }

    /**
     * Update the specified rePriority in storage.
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

        $priority = Priority::findOrFail($id);
        $priority->update([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Priority updated successfully!');
    }

    /**
     * Remove the specified rePriority from storage.
     */
    public function destroy(string $id)
    {
        $priority = Priority::findOrFail($id);
        $priority->delete();

        return redirect()->back()->with('success', 'Priority deleted successfully!');
    }
}
