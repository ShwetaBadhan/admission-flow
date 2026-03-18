<?php

namespace App\Http\Controllers;
use App\Models\DocumentSetting;

use Illuminate\Http\Request;

class DocumentSettingController extends Controller
{
      public function index()
    {
        $documentsettings = DocumentSetting::orderBy('created_at', 'desc')->paginate(10);
        return view('pages.document-settings.index', compact('documentsettings'));
    }

    /**
     * Store a newly created reDocument Settings in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'status' => 'required|in:0,1',
        ]);

        DocumentSetting::create([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Document Settings created successfully!');
    }

    /**
     * Update the specified reDocument Settings in storage.
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

        $documentsetting = DocumentSetting::findOrFail($id);
        $documentsetting->update([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Document Settings updated successfully!');
    }

    /**
     * Remove the specified reDocument Settings from storage.
     */
    public function destroy(string $id)
    {
        $documentsetting = DocumentSetting::findOrFail($id);
        $documentsetting->delete();

        return redirect()->back()->with('success', 'Document Settings deleted successfully!');
    }
}
