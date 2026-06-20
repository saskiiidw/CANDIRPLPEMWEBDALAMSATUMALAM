<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Auth\LoginForm;
use App\Livewire\Auth\RegisterForm;
use App\Livewire\CanteenOrder;
use App\Livewire\OrderHistory;
use App\Livewire\OrderStatusTracker;
use App\Livewire\ProfileEditor;
use App\Livewire\SalesReport;
use App\Livewire\SellerDashboard;
use App\Livewire\SellerVerification;
use App\Livewire\UserManagement;

// ── Auth (Guest) ─────────────────────────────────────────────────────────────
Route::get('/login', LoginForm::class)->name('login')->middleware('guest');
Route::get('/register', RegisterForm::class)->name('register')->middleware('guest');

// ── Auth Umum (Semua role yang login) ────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/profile', ProfileEditor::class)->name('profile.edit');
    // Alias agar navigation.blade.php (Breeze) tetap bisa pakai route('profile')
    Route::get('/profile', ProfileEditor::class)->name('profile');
    Route::get('/orders/history', OrderHistory::class)->name('orders.history');
    Route::get('/orders/{order}', OrderStatusTracker::class)->name('orders.track');
    Route::get('/canteen/{seller}', CanteenOrder::class)->name('canteen.order');

    // Halaman dashboard umum (redirect sesuai role)
    Route::view('/dashboard', 'dashboard')->name('dashboard');
});

// ── Penjual ───────────────────────────────────────────────────────────────────
// Halaman ini bisa diakses penjual meski belum terverifikasi (halaman tunggu)
Route::middleware('auth')->get('/seller/pending', function () {
    return view('livewire.seller-pending');
})->name('seller.pending');

Route::middleware(['auth', 'role:penjual'])->group(function () {
    Route::get('/seller/dashboard', SellerDashboard::class)->name('seller.dashboard');
    Route::get('/seller/sales-report', SalesReport::class)->name('seller.sales-report');
});

// ── Admin ─────────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', UserManagement::class)->name('admin.dashboard');
    Route::get('/admin/seller-verification', SellerVerification::class)->name('admin.seller-verification');
});

// ── Landing page ──────────────────────────────────────────────────────────────
Route::view('/', 'welcome');

require __DIR__.'/auth.php';
