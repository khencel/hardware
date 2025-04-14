<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;
use App\Models\InventoryTransaction;
use Illuminate\Support\Facades\Storage;

class InventoryController extends Controller
{

    public function index()
    {
        $items = Inventory::latest()->paginate(10);

        return view('inventories.index', compact('items'));
    }

    public function create()
    {
        return view('inventories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_name'   => 'required|string|max:255',
            'category'    => 'nullable|string|max:255',
            'quantity'    => 'required|integer|min:1',
            'unit_price'  => 'required|numeric|min:0',
            'supplier'    => 'nullable|string|max:255',
            'location'    => 'nullable|string|max:255',
            'status'      => 'required|in:in stock,out of stock',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('inventory_images', 'public');
            $validated['image'] = $imagePath;
        }

        Inventory::create($validated);

        return redirect()->route('inventories.index')->with('success', 'Item added successfully.');
    }

    public function edit(Inventory $inventory)
    {
        return view('inventories.edit', compact('inventory'));
    }

    public function update(Request $request, Inventory $inventory)
    {
        $validated = $request->validate([
            'item_name'   => 'required|string|max:255',
            'category'    => 'nullable|string|max:255',
            'quantity'    => 'required|integer|min:1',
            'unit_price'  => 'required|numeric|min:0',
            'supplier'    => 'nullable|string|max:255',
            'location'    => 'nullable|string|max:255',
            'status'      => 'required|in:in stock,out of stock',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Image validation
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            if ($inventory->image) {
                Storage::disk('public')->delete($inventory->image);
            }

            $imagePath = $request->file('image')->store('inventory_images', 'public');
            $validated['image'] = $imagePath;
        }

        $inventory->update($validated);

        return redirect()->route('inventories.index')->with('success', 'Item updated successfully.');
    }

    public function show($id)
    {
        $item = Inventory::findOrFail($id);
        return view('inventories.view', compact('item'));
    }

    public function transaction(Request $request)
    {
        $validated = $request->validate([
            'inventory_id'   => 'required|exists:inventories,id',
            'quantity'       => 'required|integer|min:1',
            'transaction_type' => 'required|in:addition,deduction',
            'used_by'        => 'nullable|string|max:255'
        ]);

        $inventory = Inventory::findOrFail($request->inventory_id);

        if ($request->transaction_type === 'addition') {
            $inventory->increment('quantity', $request->quantity);
        } else {
            if ($inventory->quantity < $request->quantity) {
                return back()->with('error', 'Not enough stock available.');
            }
            $inventory->decrement('quantity', $request->quantity);
        }

        return redirect()->route('inventories.index')->with('success', 'Inventory Transaction recorded successfully.');
    }
}
