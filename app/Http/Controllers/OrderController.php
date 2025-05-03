<?php

namespace App\Http\Controllers;

use App\Models\Food;
use App\Models\Order;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{

    public function create(request $request)
    {

        $orderDetails = $request->only([
            'customer_id',
            'cashier_id',
            'customer_name',
            'order_number',
            'date',
            'items',
            'subtotal',
            'tax',
            'total',
        ]);

        // Fetch customer
        $customer = Customer::find($orderDetails['customer_id']);
        if (!$customer) {
            return response()->json([
                'message' => 'Customer not found.',
            ], 404);
        }

        // Check if balance is enough BEFORE placing order
        if ($customer->current_balance < $orderDetails['total']) {
            return response()->json([
                'message' => 'Insufficient balance.',
                'required' => $orderDetails['total'],
                'available_balance' => $customer->current_balance,
            ], 400);
        }

        // Save the order
        $order = Order::create($orderDetails);

        // Reduce food stock
        foreach ($orderDetails['items'] as $item) {
            $food = Food::find($item['id']);
            if ($food) {
                $food->quantity -= $item['quantity'];
                $food->save();
            }
        }

        // Deduct order total from customer's balance
        $customer->current_balance -= $orderDetails['total'];
        $customer->save();

        return response()->json([
            'message' => 'Order placed successfully!',
            'order' => $order,
            'remaining_balance' => $customer->current_balance,
        ], 200);
    }
}
