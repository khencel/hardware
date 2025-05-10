<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Customer;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;
use App\Exports\ReportsExport;

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

        
        return view('report.index', compact('reports', 'customers'));
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
            ->with('cashier', 'driver') // Ensure that 'driver' is also eager-loaded
            ->get();

        $headers = [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
        ];

        // 🔥 Added 'Discount', 'Delivery Option', 'Driver' to the headers
        $columns = ['Customer Name', 'Cashier Name', 'Order Number', 'Rate Type', 'Items', 'Total', 'Date Purchase', 'Discount', 'Delivery Option', 'Driver'];

        $callback = function () use ($reports, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($reports as $report) {
                $decodedItems = is_string($report->items)
                    ? json_decode($report->items, true)
                    : $report->items;

                $items = is_array($decodedItems)
                    ? implode(', ', array_map(function ($item) {
                        return is_array($item)
                            ? (($item['quantity'] ?? 'N/A') . ' pcs - ' . ($item['name'] ?? ''))
                            : $item;
                    }, $decodedItems))
                    : $decodedItems;

                // Determine the values for 'Discount', 'Delivery Option', and 'Driver'
                $discount = $report->discount ?? 'N/A';
                $deliveryOption = $report->delivery_option ?? 'N/A';
                $driver = ($deliveryOption === 'delivery' && $report->driver) ? $report->driver->name : 'N/A';

                fputcsv($file, [
                    $report->customer_name,
                    ($report->cashier->firstname ?? '') . ' ' . ($report->cashier->lastname ?? ''),
                    $report->order_number,
                    $report->rate_type ?? 'N/A',
                    $items,
                    $report->total,
                    $report->date,
                    $discount,
                    $deliveryOption,
                    $driver
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
