<?php

namespace App\Http\Controllers;
use App\Models\Intake;


use Illuminate\Http\Request;

class IntakeController extends Controller
{
    public function index()
    {
        $intakes = Intake::orderBy('created_at', 'desc')->paginate(10);
        return view('pages.intakes.index', compact('intakes'));
    }

    /**
     * Store a newly created reIntake in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'status' => 'required|in:0,1',
        ]);

        Intake::create([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Intake created successfully!');
    }

    /**
     * Update the specified reIntake in storage.
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

        $intake = Intake::findOrFail($id);
        $intake->update([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Intake updated successfully!');
    }

    /**
     * Remove the specified reIntake from storage.
     */
    public function destroy(string $id)
    {
        $intake = Intake::findOrFail($id);
        $intake->delete();

        return redirect()->back()->with('success', 'Intake deleted successfully!');
    }
}
