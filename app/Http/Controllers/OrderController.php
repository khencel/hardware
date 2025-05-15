<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Food;
use App\Models\Hold;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Quotation;
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
            'discount',
            'payment_method',
            'reference_number',
        ]);
    
        // Fetch customer from the database
        $customer = Customer::find($orderDetails['customer_id']);
        if (!$customer) {
            return response()->json([
                'message' => 'Customer not found.',
            ], 404);
        }
    
        // Set rate_type to null (if needed, you can update this later)
        $orderDetails['rate_type'] = 'null';
    
        // Check balance only for Credit payment method
        if (strtolower($orderDetails['payment_method']) === 'credit') {
            if ($customer->current_balance < $orderDetails['total']) {
                return response()->json([
                    'message' => 'Insufficient balance.',
                    'required' => $orderDetails['total'],
                    'available_balance' => $customer->current_balance,
                ], 400);
            }
        }
    

      
        // Save the order to the database
        $order = Order::create($orderDetails);
    
        // Reduce food stock based on the order items
        foreach ($orderDetails['items'] as $item) {
            $food = Food::find($item['id']);
            if ($food) {
                $food->quantity -= $item['quantity'];
                $food->save();
            }
        }
    
        // Deduct balance if payment method is Credit
        if (strtolower($orderDetails['payment_method']) === 'credit') {
            $customer->current_balance -= $orderDetails['total'];
            $customer->save();
        }
    
        // Return a success response with order details
        return response()->json([
            'message' => 'Order placed successfully!',
            'order' => $order,
            'payment_method' => $orderDetails['payment_method'],
            'remaining_balance' => $customer->current_balance,
        ], 200);
    }
    
    
    public function holdOrders(Request $request)
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
    
        // Check if order_number exists
        if (!empty($holdDetails['order_number'])) {
            $existingHoldOrder = Hold::where('order_number', $holdDetails['order_number'])->first();
            if ($existingHoldOrder) {
                // Update the existing hold order
                $existingHoldOrder->update($holdDetails);
        
                return response()->json([
                    'message' => 'Hold order updated successfully!',
                    'hold_order' => $existingHoldOrder,
                ], 200);
            }
        }else {
            // Generate a new order number
            $holdDetails['order_number'] = 'HOLD-' . time();
        }

    
        // Otherwise, create a new hold order
        $newHoldOrder = Hold::create($holdDetails);
    
        return response()->json([
            'message' => 'Order held successfully!',
            'hold_order' => $newHoldOrder,
        ], 201);
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
        return $this->getOrderById(Hold::class, $id);
    }

    public function getQuotationOrderById($id)
    {
        return $this->getOrderById(Quotation::class, $id);
    }

    private function getOrderById($modelClass, $id)
    {
        $order = $modelClass::with(['cashier', 'driver'])->find($id);
        
        if (!$order) {
            return response()->json([
                'message' => 'Order not found.',
            ], 404);
        }

        return response()->json($order);
    }


    //quotation
    public function createQuotation(Request $request)
    {
        $quotationDetails = $request->only([
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
        ]);
        // Save the quotation to the database
        $quotation = Quotation::create($quotationDetails);
    
        // Return a success response with quotation details
        return response()->json([
            'message' => 'Quotation created successfully!',
            'quotation' => $quotation,
        ], 200);
    }
    public function getQuotation()
    {
        $query = Quotation::with(['cashier', 'driver']);

        $quotations = $query->paginate(10);

        return view('quotations.index', compact('quotations'));
    }
    
    public function quotationDelete($id)
    {
        // Find the quotation by ID
        $quotation = Quotation::find($id);

        if (!$quotation) {
            return redirect()->route('quotation_orders.index')->with('error', 'Quotation not found.');
        }

        // Delete the quotation
        $quotation->delete();

        // Redirect with a success message
        return redirect()->route('quotation_orders.index')->with('success', 'Quotation deleted successfully.');
    }
}
