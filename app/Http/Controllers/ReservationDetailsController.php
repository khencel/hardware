<?php

namespace App\Http\Controllers;

use App\Models\Leisure;
use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Models\RoomReservationAddon;
use App\Http\Traits\ResponseFormatter;
use App\Models\ReservationRoomDetails;
use App\Exceptions\RoomUnavailableException;

class ReservationDetailsController extends Controller
{
    use ResponseFormatter;

    public function deleteReservationRoomDetails($id)
    {
        try {
            $roomDetails = ReservationRoomDetails::find($id);

            if (!$roomDetails) {
                return $this->error([], 'Room reservation details not found.');
            }
            $logs = [
                'action' => 'delete_room',
                'message' => 'Deleted Room Reservation',
                'reservation_id' => $roomDetails->reservation_id,
                'room_id' => $roomDetails->room_id,
                'old_data' => $roomDetails
            ];

            $roomDetails->logAction($logs);
            $roomDetails->delete();
            return $this->success($roomDetails, 'Room reservation details deleted successfully.');
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage());
        }
    }

    public function extendRoom(Request $request, ReservationRoomDetails $reservationRoomDetails)
    {
        try {
            $unavailableRooms = Reservation::checkRoomAvailability([$reservationRoomDetails->room_id], $request->check_out_date, $request->check_out_date);

            $oldData = $reservationRoomDetails->getOriginal();
            if (!empty($unavailableRooms)) {
                throw new RoomUnavailableException($unavailableRooms, 'The following rooms are unavailable.');
            }

            $reservationRoomDetails->update([
                'check_out_date' => $request->check_out_date
            ]);

            $logs = [
                'action' => 'extend_room',
                'message' => 'Room successfully extended.',
                'reservation_id' => $reservationRoomDetails->reservation_id,
                'room_id' => $reservationRoomDetails->room_id,
                'old_data' => $oldData,
                'new_data' => $reservationRoomDetails

            ];

            $reservationRoomDetails->logAction($logs);

            return $this->success($reservationRoomDetails->load('reservation.addons'), 'Room extended successfully.');
        } catch (RoomUnavailableException $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'unavailable_rooms' => $e->getUnavailableRooms()
            ], 422);
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage());
        }
    }

    // public function addAddon(Request $request, $id)
    // {
    //     try {
    //         $request->validate([
    //             'addon_id' => 'required',
    //             'addon_price' => 'required'
    //         ]);
    //         $leisure = Leisure::find($request->addon_id);

    //         $roomDetails = ReservationRoomDetails::find($id);

    //         $addon = RoomReservationAddon::create([
    //             'addon_id' => $request->addon_id,
    //             'addon_price' => $request->addon_price ?? 0,
    //             'addon_details' => $leisure,
    //             'room_reservation_details_id' => $id
    //         ]);

    //         $logs = [
    //             'action' => 'add_addon',
    //             'message' => 'Addon successfully added.',
    //             'reservation_id' => $roomDetails->reservation_id,
    //             'room_id' => $roomDetails->room_id,
    //             'new_data' => $addon
    //         ];

    //         $roomDetails->logAction($logs);

    //         return $this->success($roomDetails, 'Addons added.');
    //     } catch (\Exception $e) {
    //         return $this->error([], $e->getMessage());
    //     }
    // }

    // public function updateAddon(Request $request, $id)
    // {
    //     try {
    //         $request->validate([
    //             'addon_id' => 'required',
    //             'addon_price' => 'required'
    //         ]);

    //         $addon = RoomReservationAddon::find($id);
    //         $oldData = $addon->getOriginal();
    //         if (!$addon) {
    //             return $this->error([], 'Addon not found.');
    //         }
    //         $leisure = Leisure::find($request->addon_id);
    //         $addon->update([
    //             'addon_id' => $request->addon_id,
    //             'addon_price' => $request->addon_price ?? 0,
    //             'addon_details' => $leisure,
    //             'addon_name' => $leisure->item_name
    //         ]);

    //         $roomDetails = ReservationRoomDetails::find($addon->room_reservation_details_id);

    //         $logs = [
    //             'action' => 'update_addon',
    //             'message' => 'Addon successfully updated.',
    //             'reservation_id' => $roomDetails->reservation_id,
    //             'room_id' => $roomDetails->room_id,
    //             'old_data' => $oldData,
    //             'new_data' => $addon->fresh()->toArray()
    //         ];

    //         $roomDetails->logAction($logs);

    //         return $this->success($addon, 'Addon updated.');
    //     } catch (\Exception $e) {
    //         return $this->error([], $e->getMessage());
    //     }
    // }

    // public function deleteAddon($id)
    // {
    //     try {
    //         $addon = RoomReservationAddon::find($id);

    //         if (!$addon) {
    //             return $this->error([], 'Addon not found.');
    //         }

    //         $roomDetails = ReservationRoomDetails::find($addon->room_reservation_details_id);

    //         $logs = [
    //             'action' => 'delete_addon',
    //             'message' => 'Addon successfully deleted.',
    //             'reservation_id' => $roomDetails->reservation_id,
    //             'room_id' => $roomDetails->room_id,
    //             'old_data' => $addon
    //         ];

    //         $addon->delete();

    //         $roomDetails->logAction($logs);

    //         return $this->success($addon, 'Addon deleted.');
    //     } catch (\Exception $e) {
    //         return $this->error([], $e->getMessage());
    //     }
    // }

    // public function listAddons($reservationRoomDetailsId)
    // {
    //     try {
    //         $addons = RoomReservationAddon::where('room_reservation_details_id', $reservationRoomDetailsId)->get();

    //         if ($addons->isEmpty()) {
    //             return $this->error([], 'No addons found for this reservation room details ID.');
    //         }

    //         return $this->success($addons, 'Addons retrieved successfully.');
    //     } catch (\Exception $e) {
    //         return $this->error([], $e->getMessage());
    //     }
    // }

}
