<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContactStage;
use Illuminate\Validation\Rule;
class ContactStageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       $stages = ContactStage::orderBy('created_at', 'desc')->paginate(10);
        return view('pages.contact-stage.index', compact('stages'));
    }

    /**
     * Show the form for creating a new resource.
     */
   
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       $request->validate([
            'name' => 'required|string|max:100|unique:lead_sources,name',
            'status' => 'required|in:0,1',
        ]);
         ContactStage::create([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Lead Stage created successfully!');
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
                Rule::unique('contact_stages', 'name')->ignore($id),
            ],
            'status' => 'required|in:0,1',
        ]);

        $stage = ContactStage::findOrFail($id);
        $stage->update([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Contact Stage updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $stage = ContactStage::findOrFail($id);
        $stage->delete();

        return redirect()->back()->with('success', 'Contact Stage deleted successfully!');
    }
}
