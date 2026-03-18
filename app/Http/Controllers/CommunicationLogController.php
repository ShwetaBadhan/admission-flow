<?php

namespace App\Http\Controllers;
use App\Models\CommunicationLog;

use Illuminate\Http\Request;

class CommunicationLogController extends Controller
{
    public function index()
    {
        $logs = CommunicationLog::orderBy('created_at', 'desc')->paginate(10);
        return view('pages.communication-logs.index', compact('logs'));
    }

    /**
     * Store a newly created reCommunication Logs in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'status' => 'required|in:0,1',
        ]);

        CommunicationLog::create([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Communication Logs created successfully!');
    }

    /**
     * Update the specified reCommunication Logs in storage.
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

        $log = CommunicationLog::findOrFail($id);
        $log->update([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Communication Logs updated successfully!');
    }

    /**
     * Remove the specified reCommunication Logs from storage.
     */
    public function destroy(string $id)
    {
        $log = CommunicationLog::findOrFail($id);
        $log->delete();

        return redirect()->back()->with('success', 'Communication Logs deleted successfully!');
    }
}
