<?php

use App\Livewire\Admin\AdminManageNews;
use App\Livewire\Advisor\ClientPortfolioView;
use App\Livewire\User\AddAssetPage;
use App\Livewire\User\AddGoalPage;
use App\Livewire\User\BuyAssetPage;
use App\Livewire\User\ManageGoals;
use App\Livewire\User\ManagePortfolio;
use App\Livewire\User\MarketDataPage;
use App\Livewire\User\NewsPage;
use App\Livewire\User\PortfolioPage;
use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Advisor\Dashboard as AdvisorDashboard;


Route::view('/', 'welcome');

Route::middleware(['auth','verified'])->group(function() {
    Route::get('/dashboard', \App\Livewire\User\Dashboard::class)->name('dashboard');
});


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


//user pages
Route::middleware(['auth'])->group(function () {
    Route::get('/user-tool', \App\Livewire\User\Tools::class)->name('user.tools');
});
Route::middleware(['auth'])->group(function () {
    Route::get('/user-appointments', \App\Livewire\User\Appointments::class)->name('user.appointments');
});
Route::middleware(['auth'])->group(function () {
    Route::get('/user-appointments-add', \App\Livewire\User\AddAppointment::class)->name('user.appointments.add');
});
Route::middleware(['auth'])->group(function() {
    Route::get('/portfolio', PortfolioPage::class)->name('portfolio');
});
Route::middleware(['auth'])->group(function() {
    Route::get('/goals', ManageGoals::class)->name('goals.manage');

    // The new separate add page
    Route::get('/goals/add', AddGoalPage::class)->name('goals.add');
});
Route::middleware(['auth'])->group(function() {
    Route::get('/news', NewsPage::class)->name('user.news');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/market', MarketDataPage::class)->name('market');
});


// Admin pages
Route::middleware(['auth:admin'])->group(function () {
    Route::get('/admin-user-management', \App\Livewire\Admin\UserManagement::class)->name('admin.userManagement');
});
Route::middleware(['auth:admin'])->group(function () {
    Route::get('/reports', \App\Livewire\Admin\Reports::class)->name('admin.reports');
});
Route::middleware(['auth:admin'])->group(function() {
    Route::get('/admin/news', AdminManageNews::class)->name('admin.news');
});
Route::middleware(['auth:admin'])->group(function() {
    Route::get('/admin/news/create', App\Livewire\Admin\AddNews::class)->name('admin.news.add');
});


//Advisor pages
Route::middleware(['auth:advisor'])->group(function () {
    Route::get('/advisor-tool', \App\Livewire\Advisor\Tools::class)->name('advisor.tools');
});
Route::middleware(['auth:advisor'])->group(function () {
    Route::get('/advisor-appointments', \App\Livewire\Advisor\Appointments::class)->name('advisor.appointments');
});
Route::middleware(['auth:advisor'])->group(function() {
    Route::get('/advisor/client-portfolio/{clientId}', ClientPortfolioView::class)
        ->name('advisor.clientPortfolio');
});
Route::middleware(['auth:advisor'])->group(function () {
    Route::get('/advisor-appointments-add', \App\Livewire\Advisor\AddAppointment::class)->name('advisor.appointments.add');
});
Route::middleware(['auth:advisor'])->group(function() {
    Route::get('/advisor/news', \App\Livewire\Advisor\News::class)->name('advisor.news');
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
