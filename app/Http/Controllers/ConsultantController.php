<?php

namespace App\Http\Controllers;

use App\Models\Consultant;
use App\Models\State;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ConsultantController extends Controller
{
    public function index()
    {
        // ✅ Eager load state and city relationships
    $consultants = Consultant::with(['state', 'city'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        $states = State::orderBy('name')->get();
        $cities = City::orderBy('name')->get(); // Load all cities for initial edit modals
        
        
        return view('pages.consultants.index', compact('consultants', 'states', 'cities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:consultants,email',
            'phone' => 'required|string|max:20',
            'state' => 'required|exists:states,id',
            'city' => 'required|exists:cities,id',
            'address' => 'required|string',
            'status' => 'required|in:0,1',
        ]);

        Consultant::create($request->all());
        return redirect()->back()->with('success', 'Consultant created successfully!');
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => ['required', 'email', Rule::unique('consultants', 'email')->ignore($id)],
            'phone' => 'required|string|max:20',
            'state' => 'required|exists:states,id',
            'city' => 'required|exists:cities,id',
            'address' => 'required|string',
            'status' => 'required|in:0,1',
        ]);

        $consultant = Consultant::findOrFail($id);
        $consultant->update($request->all());
        return redirect()->back()->with('success', 'Consultant updated successfully!');
    }

    public function destroy(string $id)
    {
        $consultant = Consultant::findOrFail($id);
        $consultant->delete();
        return redirect()->back()->with('success', 'Consultant deleted successfully!');
    }



    // API Endpoint for Dependent Dropdown
public function getCitiesByState($stateId)
{
    $cities = City::where('state_id', $stateId)
                  ->orderBy('name')
                  ->get(['id', 'name']); // Only fetch needed columns
    
    return response()->json($cities);
}
}