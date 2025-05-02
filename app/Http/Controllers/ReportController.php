<?php

namespace App\Http\Controllers;

use App\Exports\ReportsExport;
use App\Models\Order;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(request $request)
    {

        $query = Order::with('cashier');

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }

        $reports = $query->paginate(10);

        return view('report.index', compact('reports'));
    }

    /**
     * Show the form for gene
     * */
    public function export(Request $request)
    {
        $fileName = 'reports.csv';

        $reports = Order::query()
            ->when($request->start_date && $request->end_date, function ($query) use ($request) {
                $query->whereBetween('date', [$request->start_date, $request->end_date]);
            })
            ->get();

        $headers = [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
        ];

        $columns = ['Customer Name', 'Cashier Name', 'Order Number', 'Items', 'Total', 'Date Purchase'];

        $callback = function () use ($reports, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($reports as $report) {
                // Decode items if it's a JSON string
                $decodedItems = is_string($report->items)
                    ? json_decode($report->items, true)
                    : $report->items;

                // Handle item display
                $items = is_array($decodedItems)
                    ? implode(', ', array_map(function ($item) {
                        return is_array($item) ? ($item['name'] ?? json_encode($item)) : $item;
                    }, $decodedItems))
                    : $decodedItems;
                // Write CSV row
                fputcsv($file, [
                    $report->customer_name,
                    ($report->cashier->firstname ?? '') . ' ' . ($report->cashier->lastname ?? ''),
                    $report->order_number,
                    // Build a string for items with quantities
                    implode(', ', array_map(function ($item) {
                        // Check if the item is an array and includes 'name' and 'quantity'
                        if (is_array($item)) {
                            return ($item['quantity'] ?? 'N/A') . ' pcs-' . ' ' . $item['name'];
                        }
                        return $item;  // If it's not an array, return the item directly
                    }, $report->items)),
                    $report->total,
                    $report->date,
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
