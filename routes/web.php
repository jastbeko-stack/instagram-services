<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SmmOrderController;
use App\Http\Controllers\UsernameMarketplaceController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/usernames', [UsernameMarketplaceController::class, 'index'])->name('marketplace.index');
Route::get('/usernames/{id}', [UsernameMarketplaceController::class, 'show'])->name('marketplace.show');
Route::get('/services', [SmmOrderController::class, 'servicesList'])->name('smm.servicesList');

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Authenticated user routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Instagram Usernames
    Route::post('/usernames/{id}/buy', [UsernameMarketplaceController::class, 'buy'])->name('marketplace.buy');
    Route::get('/usernames/order/{id}', [UsernameMarketplaceController::class, 'orderSuccess'])->name('marketplace.orderSuccess');
    Route::get('/my-usernames', [UsernameMarketplaceController::class, 'myUsernames'])->name('marketplace.myUsernames');

    // SMM Orders
    Route::get('/order-services', [SmmOrderController::class, 'index'])->name('smm.index');
    Route::post('/order-services', [SmmOrderController::class, 'store'])->name('smm.store');
    Route::get('/my-orders', [SmmOrderController::class, 'myOrders'])->name('smm.myOrders');

    // Wallet & Crypto USDT Deposits
    Route::get('/wallet', [WalletController::class, 'index'])->name('wallet.index');
    Route::get('/wallet/deposit', [WalletController::class, 'depositForm'])->name('wallet.deposit');
    Route::post('/wallet/deposit', [WalletController::class, 'submitDeposit'])->name('wallet.submitDeposit');

    // Daily Tasks & Rewards (Earn Points)
    Route::get('/tasks', [\App\Http\Controllers\TaskController::class, 'index'])->name('tasks.index');
    Route::post('/tasks/{id}/complete', [\App\Http\Controllers\TaskController::class, 'complete'])->name('tasks.complete');
    Route::post('/tasks/claim-bonus', [\App\Http\Controllers\TaskController::class, 'claimDailyBonus'])->name('tasks.claimBonus');
    Route::post('/tasks/convert', [\App\Http\Controllers\TaskController::class, 'convertPoints'])->name('tasks.convert');
});

// Admin Panel routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    // Usernames Management
    Route::get('/usernames', [AdminController::class, 'usernamesIndex'])->name('usernames.index');
    Route::get('/usernames/create', [AdminController::class, 'usernameCreate'])->name('usernames.create');
    Route::post('/usernames', [AdminController::class, 'usernameStore'])->name('usernames.store');
    Route::get('/usernames/{id}/edit', [AdminController::class, 'usernameEdit'])->name('usernames.edit');
    Route::put('/usernames/{id}', [AdminController::class, 'usernameUpdate'])->name('usernames.update');
    Route::delete('/usernames/{id}', [AdminController::class, 'usernameDestroy'])->name('usernames.destroy');

    // SMM Categories
    Route::get('/categories', [AdminController::class, 'categoriesIndex'])->name('categories.index');
    Route::post('/categories', [AdminController::class, 'categoryStore'])->name('categories.store');
    Route::delete('/categories/{id}', [AdminController::class, 'categoryDestroy'])->name('categories.destroy');

    // SMM Services
    Route::get('/services', [AdminController::class, 'servicesIndex'])->name('services.index');
    Route::post('/services', [AdminController::class, 'serviceStore'])->name('services.store');
    Route::put('/services/{id}', [AdminController::class, 'serviceUpdate'])->name('services.update');
    Route::delete('/services/{id}', [AdminController::class, 'serviceDestroy'])->name('services.destroy');

    // SMM Providers
    Route::get('/providers', [AdminController::class, 'providersIndex'])->name('providers.index');
    Route::post('/providers', [AdminController::class, 'providerStore'])->name('providers.store');
    Route::post('/providers/{id}/sync-balance', [AdminController::class, 'providerSyncBalance'])->name('providers.syncBalance');
    Route::delete('/providers/{id}', [AdminController::class, 'providerDestroy'])->name('providers.destroy');

    // SMM Orders
    Route::get('/orders', [AdminController::class, 'smmOrdersIndex'])->name('orders.index');
    Route::put('/orders/{id}', [AdminController::class, 'smmOrderUpdateStatus'])->name('orders.updateStatus');
    Route::post('/orders/{id}/retry', [AdminController::class, 'smmOrderRetryApi'])->name('orders.retry');

    // Crypto Deposits (USDT)
    Route::get('/deposits', [AdminController::class, 'depositsIndex'])->name('deposits.index');
    Route::post('/deposits/{id}/approve', [AdminController::class, 'depositApprove'])->name('deposits.approve');
    Route::post('/deposits/{id}/reject', [AdminController::class, 'depositReject'])->name('deposits.reject');

    // Users & Balance adjustments
    Route::get('/users', [AdminController::class, 'usersIndex'])->name('users.index');
    Route::post('/users/{id}/adjust-balance', [AdminController::class, 'userAdjustBalance'])->name('users.adjustBalance');

    // Tasks Management
    Route::get('/tasks', [AdminController::class, 'tasksIndex'])->name('tasks.index');
    Route::post('/tasks', [AdminController::class, 'taskStore'])->name('tasks.store');
    Route::post('/tasks/{id}/toggle', [AdminController::class, 'taskToggleStatus'])->name('tasks.toggle');
    Route::delete('/tasks/{id}', [AdminController::class, 'taskDestroy'])->name('tasks.destroy');

    // Platform Settings
    Route::get('/settings', [AdminController::class, 'settingsIndex'])->name('settings.index');
    Route::post('/settings', [AdminController::class, 'settingsUpdate'])->name('settings.update');
});
