<?php

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\GetRoles;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\LeisureController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\HistoryLogController;
use App\Http\Controllers\RestoTableController;
use App\Http\Controllers\Rooms\RoomController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\TransactionsController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\ReservationDetailsController;
use App\Http\Controllers\Rooms\RoomCategoryController;

use App\Http\Controllers\FoodCategoryController;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\OrderController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'index']); // List all users
    Route::post('/', [UserController::class, 'store']); // Create a new user
    Route::put('/{id}', [UserController::class, 'update']); // Update a user
    Route::delete('/{id}', [UserController::class, 'destroy']); // Soft delete a user
    Route::get('/{id}', [UserController::class, 'view']); // View user details
    Route::post('/restore/{id}', [UserController::class, 'restore']); // Restore a soft-deleted user
});


Route::get('/roles', GetRoles::class)->name('role.index');

Route::apiResource('room-categories', RoomCategoryController::class);
Route::apiResource('rooms', RoomController::class);
Route::apiResource('leisures', LeisureController::class);
Route::apiResource('packages', PackageController::class);
Route::apiResource('agents', AgentController::class);
Route::apiResource('resto-tables', RestoTableController::class);
Route::apiResource('reservations', ReservationController::class);


Route::put('reservations/{reservation}/update-status/{room}', [ReservationController::class, 'updateReservationRoomStatus']);
Route::post('reservations/change-room/{reservation}', [ReservationController::class, 'changeReservationRoom']);

Route::delete('reservation-rooms/{id}', [ReservationDetailsController::class, 'deleteReservationRoomDetails']);

Route::get('history-logs', [HistoryLogController::class, 'list']);
// Route::post('/reservation-rooms/{id}/addon', [ReservationDetailsController::class, 'addAddon']);
// Route::put('/reservation-rooms/addon/{id}', [ReservationDetailsController::class, 'updateAddon']);
// Route::delete('/reservation-rooms/addon/{id}', [ReservationDetailsController::class, 'deleteAddon']);
// Route::get('/reservation-rooms/{id}/addons', [ReservationDetailsController::class, 'listAddons']);
Route::put('/reservation-rooms/room/{reservationRoomDetails}/extend', [ReservationDetailsController::class, 'extendRoom']);

Route::get('reports/rooms-status', [ReportsController::class, 'room_statuses']);
Route::get('reports/walk-in/payments-summary', [ReportsController::class, 'payments_summary']);
Route::get('/reports/bookings', [ReportsController::class, 'getRoomBookings']);

Route::post('checkout', [ReservationController::class, 'checkout']);

Route::get('/categories/{category}/available-rooms', [RoomCategoryController::class, 'getAvailableRooms'])
    ->name('categories.available-rooms');

Route::post('/payments/delete', [ReservationController::class, 'voidPayment']);
Route::get('/transactions', [TransactionsController::class, 'getTransactions']);
// Route::get('reports/sales-summary',[ReportsController::class, 'sales_summary']);

Route::post('check-fo-password', function () {
    $username = request()->get('username');
    $password = request()->get('password');

    $user = User::active()->hasRole([2, 8])->where('username', $username)->first();

    if (!$user) {
        return response()->json(['status' => false, 'message' => 'User not found.'], 404);
    }

    $isPasswordValid = Hash::check($password, $user->void_password);

    return response()->json([
        'status' => $isPasswordValid,
        'message' => $isPasswordValid ? 'Password is correct.' : 'Invalid password.'
    ]);
})->middleware('throttle:5,1');



Route::get('/show-all-category', [RoomCategoryController::class, 'getAllCategory']);
Route::post('/get-schedule-data', [ReservationController::class, 'getScheduleData']);

Route::get('/payment-methods', [PaymentMethodController::class, 'showAllMethod']);


Route::post('/update-status-per-reservation_id/{reservation_id}', [ReservationController::class, 'updateRoomByReservationId']);
Route::get('/get-last-payment-for-tr-num', [ReservationController::class, 'showAllPayment']);

Route::get('/list-discount-codes', [DiscountController::class, 'listDiscountCodes']);


// khen api
Route::get('/gel-all-category', [FoodCategoryController::class, 'get_all_category']);


Route::get('/get-all-item-per-category/{category_id}', [FoodController::class, 'getItemByCategory']);

// save  orders
Route::post('/orders', [OrderController::class, 'create']);
Route::get('/hold-orders/{id}', [OrderController::class, 'getHoldOrderById']);
Route::post('/hold-orders', [OrderController::class, 'holdOrders']);

//quotation
Route::post('/quotation', [OrderController::class, 'createQuotation']);
