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
        // Validation
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:food_categories,id',
            'price' => 'nullable|numeric|min:0', // Price is nullable as it may be calculated from cost and margin
            'cost_price' => 'required|numeric|min:0', // Ensure cost price is always provided
            'margin_percentage' => 'required|numeric|min:0|max:100', // Margin percentage must be valid
            'is_available' => 'required|boolean',
            'quantity' => 'required|integer|min:0',
            'barcode' => 'required|string|size:13|unique:foods,barcode',
        ]);
    
        // Calculate the price if it's not provided (based on cost price and margin percentage)
        $costPrice = $request->cost_price;
        $marginPercentage = $request->margin_percentage;
        
        // If price is not provided, calculate it from the cost price and margin percentage
        $price = $request->price;
    
        if (!$price) {
            $price = $costPrice / (1 - ($marginPercentage / 100));
        }
    
        // Create the food record
        Food::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'price' => $price, // Store the calculated or provided price
            'cost_price' => $costPrice, // Store the cost price
            'margin_percentage' => $marginPercentage, // Store the margin percentage
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

        // Validate the incoming data
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:food_categories,id',
            'price' => 'required|numeric|min:0',
            'cost_price' => 'required|numeric|min:0', 
            'margin_percentage' => 'required|numeric|min:0',
            'is_available' => 'required|boolean',
            'quantity' => 'required|integer|min:0',
            'barcode' => 'required|string|size:13|unique:foods,barcode,' . $food->id,
        ]);

        // Check if the margin percentage is provided. If not, calculate it from price and cost price.
        if ($request->has('margin_percentage') && $request->margin_percentage !== null) {
            $marginPercentage = $request->margin_percentage;
        } else {
            // Calculate margin percentage if not provided
            $costPrice = $request->cost_price;
            $price = $request->price;

            if ($costPrice && $price && $price > $costPrice) {
                $marginPercentage = (($price - $costPrice) / $price) * 100;
            } else {
                $marginPercentage = 0; // Default margin percentage if the values are invalid
            }
        }

        $food->update([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'cost_price' => $request->cost_price,
            'margin_percentage' => $marginPercentage,
            'is_available' => $request->is_available,
            'quantity' => $request->quantity,
            'barcode' => $request->barcode,
        ]);

        // Redirect back to the foods index page with a success message
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
