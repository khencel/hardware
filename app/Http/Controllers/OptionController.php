<?php

namespace App\Http\Controllers;

use App\Models\option;
use Illuminate\Http\Request;

class OptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $option = option::paginate(10);
        return view('option.index', compact('option'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('option.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
 
        $request->merge([
            'is_active' => $request->has('is_active')
        ]);
        $request->validate([
            'name' => 'required|string|max:255|unique:options,name',
            'value' => 'nullable|string',
            'type' => 'required',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        option::create($request->all());

        return redirect()->route('option.index')->with('success', 'Option created successfully.');
    }

   
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(option $option)
    {
        return view('option.edit', compact('option'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, option $option)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:options,name,' . $option->id,
            'value' => 'nullable|string',
            'type' => 'required',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $option->update($request->all());

        return redirect()->route('option.index')->with('success', 'Option updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(option $option)
    {
        $option->delete();
        return redirect()->route('option.index')->with('success', 'Option deleted successfully.');
    }
}
