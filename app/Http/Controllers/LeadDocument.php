<?php

namespace App\Http\Controllers;

use App\Models\LeadDocument;
use App\Models\Lead;
use App\Models\DocumentSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LeadDocumentController extends Controller
{
    public function store(Request $request, Lead $lead)
    {
        $validated = $request->validate([
            'document_type_id' => 'required|exists:document_settings,id',
            'file' => 'required|file|max:5120', // 5MB
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->store('leads/' . $lead->id . '/documents', 'public');

            LeadDocument::create([
                'lead_id' => $lead->id,
                'document_type_id' => $validated['document_type_id'],
                'file_name' => $fileName,
                'file_path' => $filePath,
                'file_type' => $file->getClientOriginalExtension(),
                'file_size' => $file->getSize() / 1024, // KB
                'uploaded_by' => auth()->id(),
                'status' => 'pending',
            ]);
        }

        return redirect()->back()->with('success', 'Document uploaded successfully!');
    }

    public function updateStatus(Request $request, Lead $lead, LeadDocument $document)
    {
        if ($document->lead_id !== $lead->id) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,verified,rejected',
            'remarks' => 'nullable|string',
        ]);

        $document->update($validated);

        return redirect()->back()->with('success', 'Document status updated!');
    }

    public function destroy(Lead $lead, LeadDocument $document)
    {
        if ($document->lead_id !== $lead->id) {
            abort(403);
        }

        Storage::disk('public')->delete($document->file_path);
        $document->delete();

        return redirect()->back()->with('success', 'Document deleted successfully!');
    }

    public function download(Lead $lead, LeadDocument $document)
    {
        if ($document->lead_id !== $lead->id) {
            abort(403);
        }

        return Storage::disk('public')->download($document->file_path, $document->file_name);
    }
}
