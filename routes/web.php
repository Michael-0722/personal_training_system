<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Client;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\Trainer;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingController::class, 'index'])->name('welcome');


//Authentication
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::get('/register/trainer', [AuthController::class, 'showTrainerRegister'])->name('register.trainer');
    Route::post('/register/trainer/info', [AuthController::class, 'storeTrainerRegisterInfo'])->name('register.trainer.info.store');
    Route::get('/register/trainer/profile', [AuthController::class, 'showTrainerRegisterProfile'])->name('register.trainer.profile');
    Route::post('/register/trainer/profile', [AuthController::class, 'completeTrainerRegister'])->name('register.trainer.complete');
    Route::get('/register/client', [AuthController::class, 'showClientRegister'])->name('register.client');
    Route::post('/register', [AuthController::class, 'register']);
});
Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout')->middleware('auth');



//Admin Routes 
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/trainers', [Admin\TrainersController::class, 'index'])->name('trainers.index');
        Route::patch('/trainers/{trainer}/approve', [Admin\TrainersController::class, 'approve'])->name('trainers.approve');
        Route::patch('/trainers/{trainer}/reject', [Admin\TrainersController::class, 'reject'])->name('trainers.reject');
        Route::patch('/trainers/{trainer}/suspend', [Admin\TrainersController::class, 'suspend'])->name('trainers.suspend');
        Route::get('/clients', [Admin\ClientsController::class, 'index'])->name('clients.index');
        Route::patch('/clients/{user}/suspend', [Admin\ClientsController::class, 'suspend'])->name('clients.suspend');
        Route::get('/payouts', [Admin\PayoutsController::class, 'index'])->name('payouts.index');
        Route::post('/payouts/{transaction}/process', [Admin\PayoutsController::class, 'process'])->name('payouts.process');
        Route::get('/notifications', [Admin\NotificationsController::class, 'index'])->name('notifications.index');
        Route::patch('/notifications/{n}/read', [Admin\NotificationsController::class, 'markRead'])->name('notifications.read');
        Route::get('/settings', [Admin\SettingsController::class, 'index'])->name('settings.index');
        Route::post('/settings', [Admin\SettingsController::class, 'update'])->name('settings.update');
    });



// Trainer Pending/Rejected (before approval check) 
Route::middleware(['auth', 'role:trainer'])->group(function () {
    Route::get('/trainer/pending', fn () => view('trainer.pending'))->name('trainer.pending');
    Route::get('/trainer/rejected', fn () => view('trainer.rejected'))->name('trainer.rejected');
});



//Trainer Routes 
Route::middleware(['auth', 'role:trainer', 'trainer.approved'])
    ->prefix('trainer')->name('trainer.')->group(function () {
        Route::get('/dashboard', [Trainer\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/sessions', [Trainer\SessionsController::class, 'index'])->name('sessions.index');
        Route::post('/sessions', [Trainer\SessionsController::class, 'store'])->name('sessions.store');
        Route::put('/sessions/{session}', [Trainer\SessionsController::class, 'update'])->name('sessions.update');
        Route::delete('/sessions/{session}', [Trainer\SessionsController::class, 'destroy'])->name('sessions.destroy');
        Route::get('/availability', [Trainer\AvailabilityController::class, 'index'])->name('availability.index');
        Route::post('/availability', [Trainer\AvailabilityController::class, 'store'])->name('availability.store');
        Route::delete('/availability/{slot}', [Trainer\AvailabilityController::class, 'destroy'])->name('availability.destroy');
        Route::get('/bookings', [Trainer\BookingsController::class, 'index'])->name('bookings.index');
        Route::patch('/bookings/{booking}/confirm', [Trainer\BookingsController::class, 'confirm'])->name('bookings.confirm');
        Route::patch('/bookings/{booking}/reject', [Trainer\BookingsController::class, 'reject'])->name('bookings.reject');
        Route::get('/notifications', [Trainer\NotificationsController::class, 'index'])->name('notifications.index');
        Route::patch('/notifications/{n}/read', [Trainer\NotificationsController::class, 'markRead'])->name('notifications.read');
        Route::get('/earnings', [Trainer\EarningsController::class, 'index'])->name('earnings.index');
        Route::get('/calendar', [Trainer\CalendarController::class, 'index'])->name('calendar.index');
        Route::get('/settings', [Trainer\SettingsController::class, 'index'])->name('settings.index');
        Route::post('/settings', [Trainer\SettingsController::class, 'update'])->name('settings.update');
    });


// Client Routes 
Route::middleware(['auth', 'role:client'])
    ->prefix('client')->name('client.')->group(function () {
        Route::get('/dashboard', [Client\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/browse', [Client\BrowseController::class, 'index'])->name('browse');
        Route::get('/trainer/{trainer}', [Client\TrainerProfileController::class, 'show'])->name('trainer.profile');
        Route::get('/book/{trainer}/{session}', [Client\BookingController::class, 'show'])->name('book.show');
        Route::post('/book/{trainer}/{session}', [Client\BookingController::class, 'store'])->name('book.store');
        Route::get('/bookings', [Client\BookingsController::class, 'index'])->name('bookings.index');
        Route::post('/bookings/{booking}/cancel', [Client\BookingsController::class, 'cancel'])->name('bookings.cancel');
        Route::post('/bookings/{booking}/review', [Client\BookingsController::class, 'review'])->name('bookings.review');
        Route::get('/notifications', [Client\NotificationsController::class, 'index'])->name('notifications.index');
        Route::patch('/notifications/{n}/read', [Client\NotificationsController::class, 'markRead'])->name('notifications.read');
        Route::get('/settings', [Client\SettingsController::class, 'index'])->name('settings.index');
        Route::post('/settings', [Client\SettingsController::class, 'update'])->name('settings.update');
    });
