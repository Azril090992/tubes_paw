<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CafeController;
use App\Http\Controllers\MenuCategoryController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;

use App\Models\Cafe;
use App\Models\Menu;

// Homepage
Route::get('/', function () {
    $cafes = Cafe::with('cafeDetail', 'reviews')->where('approval_status', '!=', 'pending')->get();
    return view('welcome', compact('cafes'));
})->name('home');

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');



// CAFES
Route::resource('cafes', CafeController::class);

// MENU CATEGORY & ITEMS
Route::resource('menu-categories', MenuCategoryController::class);
Route::resource('menus', Menu::class);

// TABLES
Route::resource('tables', TableController::class);

// VOUCHERS
Route::resource('vouchers', VoucherController::class);

// BOOKINGS
Route::resource('bookings', BookingController::class);
Route::post('/bookings/{booking}/payment', [BookingController::class, 'uploadPayment'])->name('bookings.payment');
Route::get('/bookings/{booking}/menu', [BookingController::class, 'menu'])->name('bookings.menu');
Route::post('/bookings/{booking}/item', [BookingController::class, 'updateItem'])->name('bookings.updateItem');
Route::post('/bookings/{booking}/verify-order', [BookingController::class, 'verifyOrder'])->name('bookings.verifyOrder');
Route::get('/bookings/{booking}/export', [BookingController::class, 'export'])->name('bookings.export');

// ADMIN ROUTES
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');

    // Bookings
    Route::get('/bookings/export', [AdminController::class, 'exportBookings'])->name('bookings.export_all');
    Route::get('/bookings', [AdminController::class, 'bookings'])->name('bookings');
    Route::match(['put', 'patch', 'post'], '/bookings/{booking}/confirm', [AdminController::class, 'confirmPayment'])->name('bookings.confirm');
    Route::match(['put', 'patch', 'post'], '/bookings/{booking}/reject', [AdminController::class, 'rejectPayment'])->name('bookings.reject');

    // Cafes
    Route::get('/cafes', [AdminController::class, 'cafes'])->name('cafes');
    Route::get('/cafes/create', [AdminController::class, 'createCafe'])->name('cafes.create');
    Route::post('/cafes', [AdminController::class, 'storeCafe'])->name('cafes.store');
    Route::get('/cafes/{cafe}/edit', [AdminController::class, 'editCafe'])->name('cafes.edit');
    Route::put('/cafes/{cafe}', [AdminController::class, 'updateCafe'])->name('cafes.update');
    Route::delete('/cafes/{cafe}', [AdminController::class, 'deleteCafe'])->name('cafes.delete');

    // Cafe Menus & Tables (Admin manages specific cafe's resources)
    Route::get('/cafes/{cafe}/menus', [AdminController::class, 'cafeMenus'])->name('cafes.menus');
    Route::post('/cafes/{cafe}/menus', [AdminController::class, 'storeCafeMenu'])->name('cafes.menus.store');
    Route::delete('/menus/{menu}', [AdminController::class, 'deleteCafeMenu'])->name('cafes.menus.delete');

    Route::get('/cafes/{cafe}/tables', [AdminController::class, 'cafeTables'])->name('cafes.tables');
    Route::post('/cafes/{cafe}/tables', [AdminController::class, 'storeCafeTable'])->name('cafes.tables.store');
    Route::delete('/tables/{table}', [AdminController::class, 'deleteCafeTable'])->name('cafes.tables.delete');

    Route::get('/users', [AdminController::class, 'users'])->name('users');

    // Categories (Global)
    Route::get('/categories', [AdminController::class, 'categories'])->name('categories');
    Route::post('/categories', [AdminController::class, 'storeCategory'])->name('categories.store');
    Route::put('/categories/{category}', [AdminController::class, 'updateCategory'])->name('categories.update');
    Route::delete('/categories/{category}', [AdminController::class, 'deleteCategory'])->name('categories.delete');
});

// REVIEWS (only store & index)
Route::resource('reviews', ReviewController::class)->only(['index', 'store']);

// Location
Route::post('/save-location', function (\Illuminate\Http\Request $request) {
    session(['user_lat' => $request->latitude]);
    session(['user_lng' => $request->longitude]);
    return response()->json(['status' => 'Location saved']);
})->name('save-location');

// Cart Management
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
Route::patch('/cart/{id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/cart', [CartController::class, 'clear'])->name('cart.clear');
Route::delete('/cart/cafe/{cafeId}', [CartController::class, 'clearCafe'])->name('cart.clearCafe');

// API routes for AJAX
Route::get('/cart/count', [CartController::class, 'getCount'])->name('cart.count');
Route::get('/cart/total', [CartController::class, 'getTotal'])->name('cart.total');