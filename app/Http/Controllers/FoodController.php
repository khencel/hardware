<?php

namespace App\Http\Controllers;

use App\Models\Food;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\FoodCategory;

class FoodController extends Controller
{
    public function index(Request $request)
    {
        // Check if the user has the 'food' permission
        $query = Food::with('category')->latest();

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $foods = $query->paginate(10)->withQueryString(); // keep query string on pagination

        $categories = FoodCategory::all();

        return view('foods.index', compact('foods', 'categories'));
    }

    public function create()
    {
        $categories = FoodCategory::all();
        return view('foods.create', compact('categories'));
    }

    public function store(Request $request)
    {
        dd($request->all());
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:food_categories,id',
            'price' => 'required|numeric|min:0',
            'is_available' => 'required|boolean',
            'quantity' => 'required|integer|min:0',
            'barcode' => 'required|string|size:13|unique:foods,barcode',
        ]);

        Food::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'is_available' => $request->is_available,
            'quantity' => $request->quantity,
            'barcode' => $request->barcode,
        ]);

        return redirect()->route('foods.index')->with('success', 'Food added successfully.');
    }

    public function edit(Food $food)
    {
        $categories = FoodCategory::all();
        return view('foods.edit', compact('food', 'categories'));
    }

    public function update(Request $request, Food $food)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:food_categories,id',
            'price' => 'required|numeric|min:0',
            'is_available' => 'required|boolean',
            'quantity' => 'required|integer|min:0',
            'barcode' => 'required|string|size:13|unique:foods,barcode,' . $food->id,
        ]);

        // Update the food item with the validated data
        $food->update([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'is_available' => $request->is_available,
            'quantity' => $request->quantity,
            'barcode' => $request->barcode,
        ]);


        return redirect()->route('foods.index')->with('success', 'Food updated successfully.');
    }

    public function destroy(Food $food)
    {
        $food->delete();
        return redirect()->route('foods.index')->with('success', 'Food deleted successfully.');
    }

    public function getItemByCategory($category_id)
    {
        $data = Food::where('category_id', $category_id)->get();
        return $data;
    }
}
