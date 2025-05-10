<?php

namespace App\Http\Controllers;

use App\Models\Tax;
use Illuminate\Http\Request;

class TaxController extends Controller
{
    public function index()
    {
        return view('taxes.index', ['tax' => Tax::latest()->paginate(5)]);
    }

    public function create()
    {
        return view('taxes.create');
    }

    public function edit(Tax $Tax)
    {
        return view('taxes.edit', compact('Tax'));
    }

    public function update(Request $request, Tax $tax)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'percentage' => 'required|numeric|min:0|max:100',
            'description' => 'nullable|string',
        ]);
    
        // If the updated tax is set to be active
        if ($request->has('is_active') && $request->is_active) {
            // Deactivate any other active tax before updating the current tax
            Tax::where('is_active', true)->update(['is_active' => false]);
    
            // Mark this tax as active
            $tax->is_active = true;
        } else {
            // If the tax is not active, make sure it's deactivated
            $tax->is_active = false;
        }
    
        // Update the tax with the validated data and the active status
        $tax->update($validated);
    
        return redirect()->route('taxes.index')->with('success', 'Tax updated successfully.');
    }
    

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'percentage' => 'required|numeric|min:0|max:100',
            'description' => 'nullable|string',
        ]);
    
        // Check if the 'is_active' field is true in the request and deactivate other active taxes
        if ($request->has('is_active') && $request->is_active) {
            // Deactivate any existing active tax before creating a new one
            Tax::where('is_active', true)->update(['is_active' => false]);
        }
    
        // Create a new Tax record with the validated data
        $tax = Tax::create($validated);
    
        // If the new tax is set to be active, make it active
        if ($request->has('is_active') && $request->is_active) {
            $tax->is_active = true;
            $tax->save();
        }
    
        return redirect()->route('taxes.index')->with('success', 'Tax created successfully.');
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tax $Tax)
    {
        $Tax->delete();
        return redirect()->route('taxes.index')->with('success', 'Food deleted successfully.');
    }
}
