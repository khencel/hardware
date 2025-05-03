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

        $query = Order::with('cashier');

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
        $fileName = 'reports.csv';

        $reports = Order::query()
            ->when($request->start_date && $request->end_date, function ($query) use ($request) {
                $query->whereBetween('date', [$request->start_date, $request->end_date]);
            })
            ->when($request->customer_id, function ($query) use ($request) {
                $query->where('customer_id', $request->customer_id);
            })
            ->with('cashier')
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

                fputcsv($file, [
                    $report->customer_name,
                    ($report->cashier->firstname ?? '') . ' ' . ($report->cashier->lastname ?? ''),
                    $report->order_number,
                    $items,
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
