<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DiscountController extends Controller
{
    public function index()
    {
        $discounts = Discount::latest()->paginate(5);
        return view('discounts.index', compact('discounts'));
    }

    public function create()
    {
        return view('discounts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title_name' => 'required|string|max:255',
            'discount' => [
                'required',
                'numeric',
                'min:0',
                Rule::when($request->has('is_percentage'), ['max:100']),
            ],
            'is_percentage' => 'sometimes|boolean',
        ]);

        Discount::create($request->all());

        return redirect()->route('discounts.index')->with('success', 'Discount added successfully.');
    }

    public function edit(Discount $discount)
    {
        return view('discounts.edit', compact('discount'));
    }

    public function update(Request $request, Discount $discount)
    {
        $request->validate([
            'title_name' => 'required|string|max:255',
            'discount' => [
                'required',
                'numeric',
                'min:0',
                Rule::when($request->has('is_percentage'), ['max:100']),
            ],
            'is_percentage' => 'sometimes|boolean',
        ]);
        
        $discount->update($request->all());

        return redirect()->route('discounts.index')->with('success', 'Discount updated successfully.');
    }

    public function destroy(Discount $discount)
    {
        $discount->delete();
        return redirect()->route('discounts.index')->with('success', 'Discount deleted successfully.');
    }

    public function listDiscountCodes()
    {
        $discounts = Discount::latest()->get()
                    ->map(function($discount){
                        $data = [
                            'id' => $discount->id,
                            'title_name' => $discount->title_name
                        ];

                        $data['value'] = $discount->is_percentage ? number_format($discount->discount, 0) . '%' :  $discount->discount;
                        return $data;
                    });
        return response()->json($discounts);
    }
}
