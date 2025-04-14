<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\PurchaseOrder;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        return view('purchase_orders.index', ['purchaseOrders' => PurchaseOrder::with('customer')->get()]);
    }

    public function create()
    {
        return view('purchase_orders.create', ['customers' => Customer::all()]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'po_number' => 'required|unique:purchase_orders',
            'order_date' => 'required|date',
            'status' => 'required|in:Pending,Approved,Completed,Cancelled',
        ]);

        PurchaseOrder::create($request->all());
        return redirect()->route('purchase-orders.index')->with('success', 'Purchase Order created.');
    }
}
