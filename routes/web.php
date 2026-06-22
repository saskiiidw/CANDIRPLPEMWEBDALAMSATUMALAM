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
Route::middleware('guest')->group(function () {
Route::get('/register', function () {
        return view('register-choice');
    })->name('register.choice');

    Route::get('/register/{role}', RegisterForm::class)
        ->whereIn('role', ['mahasiswa', 'penjual'])
        ->name('register');
});

// ── Auth Umum (Semua role yang login) ────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/profile', function () {
        $role = auth()->user()->role;
        if ($role === 'penjual') {
            return redirect()->route('seller.dashboard');
        }
        if ($role === 'admin') {
            return redirect()->route('admin.profile');
        }
        return redirect()->route('profile.edit');
    })->name('profile');

    Route::get('/profile/edit', ProfileEditor::class)->name('profile.edit');
    Route::get('/orders/history', OrderHistory::class)->name('orders.history');
    Route::get('/orders/{order}', OrderStatusTracker::class)->name('orders.track');
    Route::get('/canteen/{seller}', CanteenOrder::class)->name('canteen.order');

    // Halaman dashboard umum (redirect sesuai role)
    Route::get('/dashboard', function () {
        $role = auth()->user()->role;
        if ($role === 'penjual') {
            return redirect()->route('seller.dashboard');
        }
        if ($role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        // mahasiswa: tampilkan StudentHome
        return redirect()->route('home.student');
    })->name('dashboard');

    // Mahasiswa: home via Livewire full-page component
    Route::get('/home', \App\Livewire\StudentHome::class)->name('home.student');
});

// ── Penjual ───────────────────────────────────────────────────────────────────
// Halaman ini bisa diakses penjual meski belum terverifikasi (halaman tunggu)
Route::middleware('auth')->get('/seller/pending', function () {
    return view('livewire.seller-pending');
})->name('seller.pending');

Route::middleware(['auth', 'role:penjual'])->group(function () {
    Route::get('/seller/dashboard', SellerDashboard::class)->name('seller.dashboard');
    Route::get('/seller/sales-report', SalesReport::class)->name('seller.sales-report');
    Route::get('/seller/report/export-pdf', [\App\Http\Controllers\SellerReportController::class, 'exportPdf'])->name('seller.report.export-pdf');
});

// ── Admin ─────────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', \App\Livewire\AdminDashboard::class)->name('admin.dashboard');
    Route::get('/admin/seller-verification', \App\Livewire\SellerVerification::class)->name('admin.seller-verification');
    Route::get('/admin/user-management', \App\Livewire\UserManagement::class)->name('admin.user-management');
    Route::get('/admin/audit-log', \App\Livewire\AdminAuditLog::class)->name('admin.audit-log');
    Route::get('/admin/profile', \App\Livewire\AdminProfile::class)->name('admin.profile');
});

// ── Landing page ──────────────────────────────────────────────────────────────
Route::view('/', 'welcome');

require __DIR__.'/auth.php';
