<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ReservationPayments;
use App\Http\Traits\ResponseFormatter;
use App\Models\ReservationRoomDetails;

class ReportsController extends Controller
{
    use ResponseFormatter;
    public function room_statuses()
    {
        $roomsByCategory = Room::with('category:id,display_name,availability')
            ->orderBy('room_category_id')
            ->get()
            ->groupBy(fn($room) => $room->category->display_name)
            ->map(fn($rooms) => [
                'all_rooms' => $rooms->pluck('id')->toArray(),
                'out_of_service' => $rooms->where('availability', false)->pluck('id')->toArray()
            ]);

        $today = request()->get('date', now()->toDateString());

        $occupiedRoomIds = ReservationRoomDetails::whereDate('check_in_date', '<=', $today)
            ->whereDate('check_out_date', '>=', $today)
            ->where('status', 'checkin')
            ->pluck('room_id')
            ->toArray();

        $bookedRoomIds = ReservationRoomDetails::where('status', 'booked')
            ->pluck('room_id')
            ->toArray();

        $statusCounts = [];

        foreach ($roomsByCategory as $categoryName => $roomData) {
            $allRooms = $roomData['all_rooms'];
            $outOfServiceRooms = $roomData['out_of_service'];

            $availableRooms = array_diff($allRooms, $occupiedRoomIds, $outOfServiceRooms);
            $occupiedRooms = array_intersect($allRooms, $occupiedRoomIds);
            $bookedRooms = array_intersect($allRooms, $bookedRoomIds);

            $statusCounts[] = [
                'category_name' => $categoryName,
                'total' => count($allRooms),
                'available' => count($availableRooms),
                'occupied' => count($occupiedRooms),
                'out_of_service' => count($outOfServiceRooms),
                'reserved' => count($bookedRooms)
            ];
        }

        return $this->success([
            "sales_summary" => $this->sales_summary($today),
            "room_statuses" =>  $statusCounts
        ]);
    }


    private function sales_summary($date)
    {
        $payments = ReservationPayments::select('initial_payment', 'user_name', 'user_id')
            ->where(function ($query) use ($date) {
                if (request()->filled('from') && request()->filled('to')) {
                    $startDate = Carbon::createFromFormat('m-d-Y', request('from'))->format('Y-m-d');
                    $endDate = Carbon::createFromFormat('m-d-Y', request('to'))->addDay()->format('Y-m-d');

                    $query->whereBetween('created_at', [$startDate, $endDate]);
                }

                if (request()->filled('year')) {
                    $query->orWhereYear('created_at', request()->get('year'));
                }

                if (!request()->filled('year') && !request()->filled('from') && !request()->filled('to')) {
                    $query->orWhereDate('created_at', $date);
                }
            })
            ->get();

        $userIdWithSales = array_unique($payments->pluck('user_id')->toArray());
        $usersWithoutSales  = User::hasRole([2])->whereNotIn('id', $userIdWithSales)->get();

        $reports = $payments->groupBy('user_id')
            ->mapWithKeys(function ($group, $userId) {
                return [
                    $userId => [
                        'user_id' => $userId,
                        'user_name' => $group->first()->user_name,
                        'sales' => $group->sum('initial_payment'),
                    ],
                ];
            });

        $usersWithoutSales = collect($usersWithoutSales)->mapWithKeys(function ($user, $userId) {
            return [
                $user->id = [
                    'user_id' => $user->id,
                    'user_name' => $user->firstname . ' ' . $user->lastname,
                    'sales' => 0,
                ]
            ];
        });

        $reports = $reports->union($usersWithoutSales)->sortByDesc('sales')->values();
        return $reports;
    }

    public function payments_summary()
    {
        try {
            $date = request()->get('date', now());
            $payments = ReservationPayments::whereDate('created_at', $date)
                ->whereNull('reservation_id')
                ->paginate(10);
            return $this->success($payments, 'Successfully fetched payments.');
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage());
        };
    }


    public function getRoomBookings(Request $request)
    {
        $bookings = ReservationRoomDetails::query()
            ->paid()
            ->join('rooms', 'reservation_room_details.room_id', '=', 'rooms.id')
            ->when($request->type === 'range', function ($query) use ($request) {
                $startDate = $request->from
                    ? Carbon::createFromFormat('m-d-Y', $request->from)->startOfDay()
                    : Carbon::now()->startOfMonth();

                $endDate = $request->to
                    ? Carbon::createFromFormat('m-d-Y', $request->to)->endOfDay()
                    : Carbon::now()->endOfMonth();

                $query->whereHas('reservation.payments', function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('created_at', [$startDate, $endDate]);
                });
            }, function ($query) use ($request) {
                $year = $request->year ?? now()->year;

                $query->whereHas('reservation.payments', function ($q) use ($year) {
                    $q->whereYear('created_at', $year);
                });
            })
            ->when($request->filled('room_category_id'), function ($q) use ($request) {
                $q->roomCategory($request->room_category_id);
            })
            ->selectRaw('
                reservation_room_details.room_id, 
                rooms.name as room_name,
                COUNT(*) as total_bookings, 
                SUM(DATEDIFF(check_out_date, check_in_date) * JSON_UNQUOTE(JSON_EXTRACT(room_details, "$.price"))) as total_sales
            ')
            ->groupBy('reservation_room_details.room_id', 'rooms.name')
            ->get();

        $roomIds = $bookings->map(function ($booking) {
            return $booking->room_id;
        })->toArray();

        $roomsWithoutTransactions = Room::whereNotIn('id', $roomIds)
            ->when($request->filled('room_category_id'), function ($q) use ($request) {
                $q->where('room_category_id', $request->room_category_id);
            })
            ->get()
            ->map(function ($room) {
                return [
                    'room_id' => $room->id,
                    'room_name' => $room->name,
                    'total_bookings' => 0,
                    'total_sales' => 0,
                ];
            });
        $allRooms = array_merge($bookings->toArray(), $roomsWithoutTransactions->toArray());
        $allRooms = collect($allRooms)->sortByDesc('total_sales');

        return response()->json($allRooms);
    }
}
