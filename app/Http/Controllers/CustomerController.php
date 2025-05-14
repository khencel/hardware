<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        return view('customers.index', ['customers' => Customer::latest()->paginate(10)]);
    }

    public function create()
    {

        return view('customers.create');
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:customers,email,' . $customer->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'initial_balance' => 'required|numeric|min:0',
        ]);

        $customer->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'initial_balance' => $request->initial_balance
        ]);

        return redirect()->route('customers.index')->with('success', 'Customer updated successfully.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'nullable|email|unique:customers',
            'phone' => 'nullable',
            'address' => 'nullable',
            'initial_balance' => 'required|numeric|min:0',
        ]);

        Customer::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'initial_balance' => $request->initial_balance,
            'current_balance' => $request->initial_balance,
        ]);

        return redirect()->route('customers.index')->with('success', 'Customer added.');
    }

    public function topUp(request $request){
        $request->validate([
            'amount' => 'required|numeric|min:0',
        ]);

        $customer = Customer::find($request->customer_id);
        $customer->current_balance += $request->amount;
        $customer->initial_balance += $request->amount;
        $customer->save();

        return redirect()->route('customers.index')->with('success', 'Customer balance updated successfully.');
    }
}
