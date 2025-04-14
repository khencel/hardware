<?php

namespace App\Helpers;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use App\Models\ReservationHistoryLog;

class LogHelper
{
    /**
     * Log an action to the reservation_history_logs table.
     *
     * @param mixed $model
     * @param string $action
     * @param array|null $data
     * @return ReservationHistoryLog
     */
    public static function logAction(
        $model,
        $reservation
    ) {
        $data = [
            'user_id' => Auth::id(),
            'user_name' => auth()->check() ? Auth::user()->firstname . " " .Auth::user()->lastname : "Guest",
            'reservation_id' => $reservation['reservation_id'],
            'room_id' => self::getData($reservation, 'room_id'),
            'action' => $reservation['action'],
            'message' => $reservation['message'],
            'old_data' => self::getData($reservation, 'old_data'),
            'new_data' => self::getData($reservation, 'new_data'),
        ];

        return ReservationHistoryLog::create($data);
    }

    private static function getData($array, $key)
    {
        return Arr::has($array, $key) ? $array[$key] : null;
    }
}
