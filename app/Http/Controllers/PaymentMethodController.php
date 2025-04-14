<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $paymentMethods = PaymentMethod::latest()->paginate(5);
        return view('payment_methods.index', compact('paymentMethods'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('payment_methods.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'status' => 'required'
        ]);

        PaymentMethod::create($request->only(['name', 'status']));

        return redirect()->route('payment-methods.index')->withSuccess('Payment Method Added Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $paymentMethod = PaymentMethod::find($id);
        return view('payment_methods.edit', compact('paymentMethod'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $paymentMethod = PaymentMethod::findOrFail($id);
        $paymentMethod->update([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return redirect()->route('payment-methods.index')->with('success', 'Payment method updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);
        $paymentMethod->delete();
        return redirect()->route('payment-methods.index')->with('success', 'Payment method deleted successfully.');
    }

    public function showAllMethod(){
        $payment_method = PaymentMethod::all();
        return $payment_method;
    }
}
