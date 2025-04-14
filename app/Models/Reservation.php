<?php

namespace App\Models;

use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Model;
use App\Exceptions\RoomUnavailableException;
use App\Http\Traits\LogsActions;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reservation extends Model
{
    use HasFactory, SoftDeletes, LogsActions;

    protected $guarded = [];

    public function reservationDetails()
    {
        return $this->hasMany(ReservationRoomDetails::class);
    }

    public function addons()
    {
        return $this->hasMany(RoomReservationAddon::class, 'reservation_id');
    }

    public function payments()
    {
        return $this->hasMany(ReservationPayments::class, 'reservation_id');
    }

    /**
     * Create reservation with Room Details
     *
     * @param array $data
     * @return Reservation
     */
    public static function createWithDetails(array $data): Reservation
    {
        $rooms = $data['guests'];
        $checkInDate = $data['check_in_date'];
        $checkOutDate = $data['check_out_date'];
        $roomIds = Arr::pluck($rooms, 'room_id');

        $unavailableRooms = self::checkRoomAvailability($roomIds, $checkInDate, $checkOutDate);

        if (!empty($unavailableRooms)) {
            throw new RoomUnavailableException($unavailableRooms, 'The following rooms are unavailable.');
        }

        $reservationData = Arr::except($data, ['check_in_date', 'check_out_date', 'room_id', 'room', 'guests', 'addons']);
        $reservation = self::create($reservationData);
        $reservation->addReservationDetails($rooms, $checkInDate, $checkOutDate, 'Created Reservation');
        $reservation->attachAddon($reservation->id, $data['addons']);
        $logs = [
            'action' => 'create',
            'message' => 'Created Room Reservation',
            'reservation_id' => $reservation->id,
            'new_data' => $reservation->load('reservationDetails'),
        ];

        $reservation->logAction($logs);

        return $reservation;
    }

    public function attachAddon($reservation_id, $addons)
    {
        foreach ($addons as $addon) {
            $leisure = Leisure::find($addon['addon_id']);
            $addonData = [
                'addon_id' => $addon['addon_id'],
                'addon_price' => $addon['addon_price'],
                'addon_name' => $leisure->item_name,
                'addon_details' => $leisure,
                'reservation_id' => $reservation_id,
                'qty' => $addon['qty']
            ];

            RoomReservationAddon::create($addonData);
        }
    }

    public function updateGuest($otherGuests)
    {
        foreach ($otherGuests as $guest) {
            ReservationRoomDetails::where('id', $guest['id'])
                ->update(['guest' => $guest['qty']]);
        }
    }

    /**
     * Update reservation with Room Details
     *
     * @param array $data
     * @return Reservation
     **/
    public static function updateWithDetails($reservation, array $data): Reservation
    {

        $roomIds = [$data['room']['room_id']];
        $checkInDate = $data['room']['check_in_date'];
        $checkOutDate = $data['room']['check_out_date'];

        $unavailableRooms = self::checkRoomAvailability($roomIds, $checkInDate, $checkOutDate, $reservation->id ?? null);

        if (!empty($unavailableRooms)) {
            throw new RoomUnavailableException($unavailableRooms, 'The following rooms are unavailable.');
        }

        if (! empty($data['addons'])) {
            $reservation->addons()->delete();
            $reservation->attachAddon($reservation->id, $data['addons']);
        }

        $oldData = $reservation->load('reservationDetails');
        $reservation->update($data['reservation']);

        $reservation->updateReservationDetails($data['room']);

        $logs = [
            'action' => 'update',
            'message' => 'Updated Room Reservation',
            'reservation_id' => $reservation->id,
            'new_data' => $reservation->load('reservationDetails'),
            'old_data' => $oldData
        ];

        $reservation->logAction($logs);

        if (! empty($data['other_rooms'])) {
            $reservation->updateGuest($data['other_rooms']);
        }
        return $reservation;
    }
    /**
     * Check Room Availability
     *
     * @param array $roomIds
     * @param string $checkInDate
     * @param string $checkOutDate
     * @param integer|null $excludeReservationId
     * @return array
     */
    public static function checkRoomAvailability(array $roomIds, string $checkInDate, string $checkOutDate, ?int $excludeReservationId = null): array
    {
        $unavailableRooms = [];

        foreach ($roomIds as $roomId) {
            $query = ReservationRoomDetails::where('room_id', $roomId)
                ->where(function ($query) use ($checkInDate, $checkOutDate) {
                    $query->where(function ($query) use ($checkInDate, $checkOutDate) {
                        $query->where('check_in_date', '<', $checkOutDate)  // Existing check-in is before new check-out
                            ->where('check_out_date', '>', $checkInDate); // Existing check-out is after new check-in
                    })
                        ->orWhere(function ($query) use ($checkInDate, $checkOutDate) {
                            $query->where('check_in_date', '=', $checkInDate)   // Exact same check-in
                                ->where('check_out_date', '=', $checkOutDate); // Exact same check-out
                        });
                });

            if ($excludeReservationId) {
                $query->where('reservation_id', '!=', $excludeReservationId);
            }

            if ($query->exists()) {
                $room_details = $query->first();
                $unavailableRooms[] = $room_details->room_details;
            }
        }

        return $unavailableRooms;
    }




    /**
     * Add Reservation Details
     *
     * @param array $roomIds
     * @param string $checkInDate
     * @param string $checkOutDate
     * @param ?string $action
     * @return void
     */
    public function addReservationDetails(
        array $rooms,
        string $checkInDate,
        string $checkOutDate
    ): void {
        foreach ($rooms as $room) {
            $roomDetails = Room::findOrFail($room['room_id']);
            $this->reservationDetails()->create([
                'check_in_date' => $checkInDate,
                'check_out_date' => $checkOutDate,
                'room_id' => $roomDetails->id,
                'room_details' => $roomDetails,
                'guest' => $room['guest'],
                'status' => 'booked',
            ]);
        }
    }
    /**
     * Update Reservation Room Details
     *
     * @param string $roomId
     * @param string $checkInDate
     * @param string $checkOutDate
     * @param string $status
     * @return void
     */
    private function updateReservationDetails($room): void
    {
        $existingRoom  = ReservationRoomDetails::where('room_id', $room['room_id'])
            ->where('reservation_id', $this->id)
            ->first();

        if ($existingRoom) {
            $existingRoom->update([
                'check_in_date' => $room['check_in_date'],
                'check_out_date' => $room['check_out_date'],
                'room_id' => $room['room_id'],
                'status' => $room['status'],
                'guest' => $room['guest']
            ]);
        }
    }
}
