<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Advisor\Dashboard as AdvisorDashboard;


Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


// Different user profile routes
Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');
Route::prefix('admin')
    ->middleware('auth:admin')  // ensure the "admin" guard is used
    ->group(function () {
        // Use the SAME profile page
        Route::view('/profile', 'profile')->name('admin.profile');
    });
Route::prefix('advisor')
    ->middleware('auth:advisor')
    ->group(function () {
        Route::view('/profile', 'profile')->name('advisor.profile');

    });


//user tool and user appointment
Route::middleware(['auth'])->group(function () {
    Route::get('/user-tool', \App\Livewire\User\Tools::class)->name('user.tools');
});
Route::middleware(['auth'])->group(function () {
    Route::get('/user-appointments', \App\Livewire\User\Appointments::class)->name('user.appointments');
});


// Admin reports and user managements
Route::middleware(['auth:admin'])->group(function () {
    Route::get('/admin-user-management', \App\Livewire\Admin\UserManagement::class)->name('admin.userManagement');
});
Route::middleware(['auth:admin'])->group(function () {
    Route::get('/reports', \App\Livewire\Admin\Reports::class)->name('admin.reports');
});


//Advisor Tools and Appointments
Route::middleware(['auth:advisor'])->group(function () {
    Route::get('/advisor-tool', \App\Livewire\Advisor\Tools::class)->name('advisor.tools');
});
Route::middleware(['auth:advisor'])->group(function () {
    Route::get('/advisor-appointments', \App\Livewire\Advisor\Appointments::class)->name('advisor.appointments');
});



///
///different roles login section
Route::prefix('admin')
    ->name('admin.')
    ->middleware('auth:admin') // Protect with admin guard
    ->group(function () {
        Route::get('/dashboard', AdminDashboard::class)->name('dashboard');
    });
Route::prefix('advisor')
    ->name('advisor.')
    ->middleware('auth:advisor')
    ->group(function () {
        Route::get('/dashboard', AdvisorDashboard::class)->name('dashboard');
    });

require __DIR__.'/auth.php';
