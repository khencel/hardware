<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Food;
use App\Models\Hold;
use App\Models\Order;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{

    public function create(Request $request)
    {
        $orderDetails = $request->only([
            'customer_id',
            'cashier_id',
            'driver_id',
            'customer_name',
            'order_number',
            'date',
            'items',
            'subtotal',
            'tax',
            'total',
            'delivery_option',
            'discount'
        ]);
    
        // Fetch customer
        $customer = Customer::find($orderDetails['customer_id']);
        if (!$customer) {
            return response()->json([
                'message' => 'Customer not found.',
            ], 404);
        }
    
        $orderDetails['rate_type'] = 'null';
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
    
    public function holdOrders(request $request)
    {

        $holdDetails = $request->only([
            'customer_id',
            'cashier_id',
            'driver_id',
            'customer_name',
            'order_number',
            'date',
            'items',
            'subtotal',
            'tax',
            'total',
            'delivery_option',
            'discount',
            'reason'
        ]);
    
        $holdOrder = Hold::create($holdDetails);
        
        return response()->json([
            'message' => 'Order held successfully!',
            'hold_order' => $holdOrder,
        ], 200);
        
    }

    public function getHoldOrders()
    {
        $query = Hold::with(['cashier', 'driver']);

        $holdOrders = $query->paginate(10);

        // Add this if not already done
        $customers = Customer::latest()->get();

        return view('hold_orders.index', compact('holdOrders', 'customers'));

    }


   public function cancelHold($id)
    {
        // Find the hold order by ID
        $holdOrder = Hold::find($id);

        if (!$holdOrder) {
            return redirect()->route('hold_orders.index')->with('error', 'Hold order not found.');
        }

        // Delete the hold order
        $holdOrder->delete();

        // Redirect with a success message
        return redirect()->route('hold_orders.index')->with('success', 'Hold order deleted successfully.');
    }

    public function getHoldOrderById($id)
    {
        $holdOrder = Hold::with(['cashier', 'driver'])->find($id);

        if (!$holdOrder) {
            return response()->json([
                'message' => 'Hold order not found.',
            ], 404);
        }

       
        return response()->json($holdOrder);
    }
}
