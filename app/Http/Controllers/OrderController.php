<?php

namespace App\Http\Controllers;

use App\Models\Food;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        // Save the order
        $order = Order::create($orderDetails);

        // Reduce food stock quantities
        foreach ($orderDetails['items'] as $item) {
            $food = Food::find($item['id']);
            if ($food) {
                $food->quantity -= $item['quantity'];
                $food->save();
            }
        }

        return response()->json([
            'message' => 'Order saved successfully!',
            'order' => $order
        ], 200);
    }
}
