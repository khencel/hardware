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
        $query = Food::with('category');

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
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:food_categories,id',
            'price' => 'required|numeric|min:0',
            'is_available' => 'required|boolean',
        ]);

        Food::create($request->all());

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
        ]);

        $food->update($request->all());

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
