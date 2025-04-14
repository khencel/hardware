<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\ReservationPayments;

class TransactionsController extends Controller
{
    public function getTransactions(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'year' => 'nullable|integer|min:2000|max:' . date('Y'),
        ]);

        $userId = $request->user_id;

        $query = ReservationPayments::where('user_id', $userId);

        if ($request->has('year')) {
            $query->whereYear('created_at', $request->year);
        } elseif ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        } else {
            $query->whereDate('created_at', Carbon::today());
        }

        $transactions = $query->get();

        return response()->json([
            'status' => 'success',
            'transactions' => $transactions
        ]);
    }
}
