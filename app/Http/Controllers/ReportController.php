<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Customer;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;
use App\Exports\ReportsExport;
use Carbon\Carbon;
class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(request $request)
    {

        $query = Order::with(['cashier', 'driver']);


        if ($request->start_date && $request->end_date) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }

        if ($request->customer_id) {
            $query->where('customer_id', $request->customer_id);
        }

        $reports = $query->paginate(10);

        // Add this if not already done
        $customers = Customer::latest()->get();
        $users = User::latest()->get();
        
        return view('report.index', compact('reports', 'customers','users'));
    }

    /**
     * Show the form for gene
     * */
    public function export(Request $request)
    {
        $fileName = 'report-generated-' . now()->format('Y-m-d-H-i-s') . '.csv';
        $reports = Order::query()
            ->when($request->start_date && $request->end_date, function ($query) use ($request) {
                $query->whereBetween('date', [$request->start_date, $request->end_date]);
            })
            ->when($request->customer_id, function ($query) use ($request) {
                $query->where('customer_id', $request->customer_id);
            })
            ->with('cashier', 'driver') // Eager load cashier and driver
            ->get();
    
        $headers = [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
        ];
    
        // Added 'Category' to the headers
        $columns = ['Customer Name', 'Cashier Name', 'Order Number', 'Rate Type', 'Items', 'Total', 'Date Purchase', 'Discount', 'Delivery Option', 'Driver', 'Category'];
    
        $callback = function () use ($reports, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
    
            foreach ($reports as $report) {
                $decodedItems = is_string($report->items)
                    ? json_decode($report->items, true)
                    : $report->items;
    
                // Initialize variables
                $items = [];
                $categories = [];
    
                if (is_array($decodedItems)) {
                    foreach ($decodedItems as $item) {
                        // Collect item details
                        $items[] = is_array($item)
                            ? (($item['quantity'] ?? 'N/A') . ' pcs - ' . ($item['name'] ?? ''))
                            : $item;
    
                        // Check for category in each item
                        $categories[] = $item['category'] ?? 'N/A';
                    }
                } else {
                    $items[] = $decodedItems;
                    $categories[] = 'N/A';
                }
    
                // Join items and categories for display
                $itemsStr = implode(', ', $items);
                $categoryStr = implode(', ', array_unique($categories)); // Ensure unique categories if there are multiple items
    
                // Determine the values for 'Discount', 'Delivery Option', and 'Driver'
                $discount = $report->discount ?? 'N/A';
                $deliveryOption = $report->delivery_option ?? 'N/A';
                $driver = ($deliveryOption === 'delivery' && $report->driver) ? $report->driver->name : 'N/A';
    
                // Write data to the CSV
                fputcsv($file, [
                    $report->customer_name,
                    ($report->cashier->firstname ?? '') . ' ' . ($report->cashier->lastname ?? ''),
                    $report->order_number,
                    $report->rate_type ?? 'N/A',
                    $itemsStr,
                    $report->total,
                    $report->date,
                    $discount,
                    $deliveryOption,
                    $driver,
                    $categoryStr // Write the category string
                ]);
            }
    
            fclose($file);
        };
    
        return response()->stream($callback, 200, $headers);
    }

    public function printReport(request $request)
    {
        $reports = Order::query()
            ->when($request->start_date && $request->end_date, function ($query) use ($request) {
                $startDate = Carbon::parse($request->start_date)->startOfDay(); // Set the time to the beginning of the day
                $endDate = Carbon::parse($request->end_date)->endOfDay(); // Set the time to the end of the day
        
                $query->whereBetween('date', [$startDate, $endDate]);
            })
            ->when($request->user_id, function ($query) use ($request) {
                $query->where('cashier_id', $request->user_id);
            })
            ->with('cashier', 'driver')
            ->get();

            if($request->has('user_id') && $request->user_id != null){
                $users = User::where('id',$request->user_id)->first();
            }else{
                $users = null;
            }
            return response()->json([
                'message' => 'Quotation created successfully!',
                'reports' => $reports,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'user' =>   $users,
            ], 200);

    }
}
