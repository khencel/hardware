<?php

namespace App\Http\Controllers\Rooms;

use App\Models\Room;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Rooms\CreateRoomRequest;
use App\Http\Requests\Rooms\UpdateRoomRequest;

class RoomController extends Controller
{
    public function index()
    {
        try {
            $rooms = Room::when(request()->has('is_available'), function ($query) {
                            return $query->where('availability', (bool)request()->get('is_available'));
                        })->with('category')
                        ->paginate(request()->get('per_page') ?? 10);

            return response()->json([
                'message' => 'Rooms fetched successfully.',
                'rooms' => $rooms,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch rooms.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(CreateRoomRequest $request)
    {
        try {

            $room = Room::create($request->validated());

            return response()->json([
                'message' => 'Room created successfully.',
                'room' => $room->load('category'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create room.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function show(Room $room)
    {
        try {
            $room->load('category');

            return response()->json([
                'message' => 'Room fetched successfully.',
                'room' => $room,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch room.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(UpdateRoomRequest $request, Room $room)
    {
        try {
            $room->update($request->validated());

            return response()->json([
                'message' => 'Room updated successfully.',
                'room' => $room->load('category'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update room.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(Room $room)
    {
        try {
            $room->delete();

            return response()->json([
                'message' => 'Room deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete room.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
