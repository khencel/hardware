<?php

use App\Models\Food;
use App\Models\Order;
use App\Models\Customer;
use App\Models\FoodCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\GetRoles;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\LeisureController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\Authentication\Login;
use App\Http\Controllers\HistoryLogController;
use App\Http\Controllers\RestoTableController;
use App\Http\Controllers\Rooms\RoomController;
use App\Http\Controllers\Authentication\Logout;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\FoodCategoryController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\ReservationDetailsController;
use App\Http\Controllers\Rooms\RoomCategoryController;
use App\Http\Controllers\UserVerifyPasswordController;
use Carbon\Carbon;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('food-categories.index');
    }
    return view('login_page');
})->name('login');

Route::get('/roles', GetRoles::class)->name('role.index');
Route::post('/login', Login::class)->name('auth.login');


Route::middleware(['auth'])->group(function () {

    Route::get('/pos', function () {
        $products = Food::where('is_available', true)->get(); // Retrieve products where 'is_available' is true.
        $user = Auth::user();
        $categories = FoodCategory::latest()->get();
        $customers = Customer::latest()->get();
        // Check if there are products available
        if ($products->isEmpty()) {
            return redirect()->back()->with('error', 'No products available.');
        }

        return view('pos.pos_order', compact('products', 'user', 'categories', 'customers'));
    });



    Route::get('/dashboard', function () {

        $items = Food::where('is_available', true)->count();
        $user = Auth::user();
        $totalCustomers = Customer::count();

        // Total sales per month
        $monthlySales = Order::select(
            DB::raw("DATE_FORMAT(date, '%Y-%m') as month"),
            DB::raw("SUM(total) as total")
        )
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->take(7) // last 6 months
            ->get()
            ->reverse(); // to show oldest -> newest

        $chartLabels = $monthlySales->pluck('month')->map(function ($month) {
            return Carbon::parse($month . '-01')->format('F Y');
        })->toArray();

        $chartData = $monthlySales->pluck('total')->toArray();

        // Still fetch low inventory items
        $lowItemsInInventory = Food::where('quantity', '<', 5)->paginate(10);

        //total sales
        $totalSales = Order::whereYear('created_at', now()->year)->sum('total');
        //average 
        $averageMonthlySales = count($chartData) > 0 ? array_sum($chartData) / count($chartData) : 0;

        return view('dashboard.index', compact(
            'items',
            'user',
            'totalCustomers',
            'totalSales',
            'lowItemsInInventory',
            'averageMonthlySales',
            'chartLabels',
            'chartData'
        ));
    });

    Route::post('/logout', Logout::class)->name('auth.logout');

    Route::resource('foods', FoodController::class);
    Route::resource('food-categories', FoodCategoryController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('users', UserManagementController::class);
    Route::resource('purchase-orders', PurchaseOrderController::class);

    Route::get('/profile', [UserController::class, 'edit'])->name('profile.view'); //edit profile
    Route::put('/profile/update', [UserController::class, 'updateProfile'])->name('users.profile');

    Route::get('/change-password', [UserController::class, 'editPassword'])->name('password.change');
    Route::post('/change/password', [UserController::class, 'updatePassword'])->name('password.update');
    //toggle status
    Route::post('user/{id}/toggle-status', [UserManagementController::class, 'toggleStatus'])->name('user.toggle-status');

    //theme
    Route::get('/set-theme/{theme}', function ($theme) {
        session(['theme' => $theme]);
        return redirect()->back();
    });

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export-csv', [ReportController::class, 'export'])->name('reports.export.csv');
});
