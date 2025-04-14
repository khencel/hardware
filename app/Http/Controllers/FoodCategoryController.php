<?php

namespace App\Http\Controllers;

use App\Models\FoodCategory;
use Illuminate\Http\Request;

class FoodCategoryController extends Controller
{
    public function index()
    {
        $categories = FoodCategory::latest()->paginate(5);
        return view('food-categories.index', compact('categories'));
    }

    public function create()
    {
        return view('food-categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:food_categories,name',
            'is_available' => 'required|boolean',
        ]);

        FoodCategory::create($request->all());

        return redirect()->route('food-categories.index')->with('success', 'Food Category added successfully.');
    }

    public function edit(FoodCategory $foodCategory)
    {
        return view('food-categories.edit', compact('foodCategory'));
    }

    public function update(Request $request, FoodCategory $foodCategory)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:food_categories,name,' . $foodCategory->id,
            'is_available' => 'required|boolean',
        ]);

        $foodCategory->update($request->all());

        return redirect()->route('food-categories.index')->with('success', 'Food Category updated successfully.');
    }

    public function destroy(FoodCategory $foodCategory)
    {
        $foodCategory->delete();

        return redirect()->route('food-categories.index')->with('success', 'Food Category deleted successfully.');
    }

    public function get_all_category(){
        $categories = FoodCategory::all();
    
        $formattedCategories = $categories->map(function($category, $index) {
            return [
                'id' => $category->id,
                'name' => $category->name,
                'active' => $index === 0 ? true : false
            ];
        });
        
        return response()->json($formattedCategories);
    }
}
