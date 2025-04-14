<?php

namespace App\Http\Controllers;

use App\Models\ReservationHistoryLog;
use Illuminate\Http\Request;

class HistoryLogController extends Controller
{
    public function list()
    {
        $historyLogs = ReservationHistoryLog::query();

        if (request()->has('reservation_id')) {
            $historyLogs = $historyLogs->where('reservation_id', request()->get('reservation_id'));
        }

        $historyLogs = $historyLogs->get();
            // ->map(function ($log) {
            //     return collect([
            //         'action' => $log->action,
            //         'user' => $log->user_name,
            //         'reservation_id' => $log->reservation_id,
            //         'room_id' => $log->room_id,
            //         'message' => $log->message,
            //         'log_id' => $log->id,

            //     ]);
            // });
        return $historyLogs;
    }
}
