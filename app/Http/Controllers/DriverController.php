<?php

namespace App\Http\Controllers;

use App\Models\Tax;
use App\Models\Food;
use App\Models\Hold;
use App\Models\Order;
use App\Models\Driver;
use App\Models\Customer;
use App\Models\Discount;
use App\Models\Quotation;
use App\Models\FoodCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\User;

class DriverController extends Controller
{
    public function index()
    {
        return view('drivers.index', ['driver' => Driver::latest()->paginate(5)]);
    }

    public function create()
    {

        return view('drivers.create');
    }

    public function edit(Driver $driver)
    {
        return view('drivers.edit', compact('driver'));
    }

    public function update(Request $request, Driver $driver)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:drivers,email,' . $driver->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        $driver->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return redirect()->route('drivers.index')->with('success', 'Driver updated successfully.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'nullable|email|unique:drivers',
            'phone' => 'nullable',
            'address' => 'nullable',
        ]);

        Driver::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return redirect()->route('drivers.index')->with('success', 'Driver added.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Driver $driver)
    {
        $driver->delete();
        return redirect()->route('drivers.index')->with('success', 'Driver deleted successfully.');
    }

    public function counts()
    {
        $data = [
            'countHold' => Hold::count(),
            'countQuotation' => Quotation::count(),
            'countCustomer' => Customer::count(),
            'countDriver' => Driver::count(),
            'countFood' => Food::count(),
            'countCategory' => FoodCategory::count(),
            'countDiscount' => Discount::count(),
            'countTax' => Tax::count(),
            'countOrder' => Order::count(),
            'countUser' => User::count(),
        ];
    
        return response()->json([
            'status' => true,
            'data' => $data,
        ]);
    }
}
