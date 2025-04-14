<?php

namespace App\Http\Controllers\Rooms;

use Carbon\Carbon;
use App\Models\RoomCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Rooms\CreateRoomCategoryRequest;
use App\Http\Requests\Rooms\UpdateRoomCategoryRequest;

class RoomCategoryController extends Controller
{
    public function index()
    {
        try {
            $categories = RoomCategory::when(request()->has('is_available'), function ($query) {
                            return $query->where('availability', (bool)request()->get('is_available'));
                        })->with('rooms')
                        ->paginate(request()->get('per_page') ?? 10);

            return response()->json([
                'message' => 'Room categories fetched successfully.',
                'categories' => $categories,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch room categories.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(CreateRoomCategoryRequest $request)
    {
        try {
            $category = RoomCategory::create($request->validated());

            return response()->json([
                'message' => 'Room category created successfully.',
                'category' => $category->load('rooms'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create room category.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            if ($id < 1) {
                $roomCategory = RoomCategory::first();
            } else {
                $roomCategory = RoomCategory::find($id);
            }

            $roomCategory->load('rooms');

            return response()->json([
                'message' => 'Room category fetched successfully.',
                'category' => $roomCategory,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch room category.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(UpdateRoomCategoryRequest $request, RoomCategory $roomCategory)
    {
        try {
            $roomCategory->update($request->validated());

            return response()->json([
                'message' => 'Room category updated successfully.',
                'category' => $roomCategory->load('rooms'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update room category.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(RoomCategory $roomCategory)
    {
        try {
            $roomCategory->delete();

            return response()->json([
                'message' => 'Room category deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete room category.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getAvailableRooms(Request $request, $categoryId)
    {
        $checkIn = $request->has('start') ? Carbon::parse($request->start) : Carbon::now();
        $checkOut = $request->has('end') ? Carbon::parse($request->end) : Carbon::now()->addDays(5);

        $category = RoomCategory::with('rooms')->findOrFail($categoryId);

        $availableRooms = $category->rooms()->whereDoesntHave('reservationRoomDetails', function ($query) use ($checkIn, $checkOut) {
            $query->where(function ($query) use ($checkIn, $checkOut) {
                $query->where('check_in_date', '<', $checkOut)
                    ->where('check_out_date', '>', $checkIn)
                    ->whereNotIn('status', ['checkout']);
            });
        })->where('availability', true)->get();

        return response()->json($availableRooms);
    }

    public function getAllCategory(){
        $category = RoomCategory::all();
        return $category;
    }
}
