<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeadSource;
use Illuminate\Validation\Rule;

class LeadSourceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sources = LeadSource::orderBy('created_at', 'desc')->paginate(10);
        return view('pages.sources.index', compact('sources'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:lead_sources,name',
            'status' => 'required|in:0,1',
        ]);

        LeadSource::create([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Source created successfully!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('lead_sources', 'name')->ignore($id),
            ],
            'status' => 'required|in:0,1',
        ]);

        $source = LeadSource::findOrFail($id);
        $source->update([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Source updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $source = LeadSource::findOrFail($id);
        $source->delete();

        return redirect()->back()->with('success', 'Source deleted successfully!');
    }

    /**
     * Toggle status (Active/Inactive) - Optional
     */
    public function toggleStatus(string $id)
    {
        $source = LeadSource::findOrFail($id);
        $source->update(['status' => $source->status == 1 ? 0 : 1]);

        return redirect()->back()->with('success', 'Status updated successfully!');
    }
}