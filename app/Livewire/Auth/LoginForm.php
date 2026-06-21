<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.guest')]
class LoginForm extends Component
{
    public string $email = '';
    public string $password = '';
    public string $errorMessage = '';

    public function login(): void
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $throttleKey = Str::lower($this->email).'|'.request()->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $this->errorMessage = 'Akun terkunci. Coba lagi dalam '.ceil($seconds / 60).' menit.';
            return;
        }

        if (! Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            RateLimiter::hit($throttleKey, 900); // lock 15 menit setelah 5x gagal (FR-001)
            $this->errorMessage = 'Email tidak terdaftar atau kata sandi tidak sesuai.';
            return;
        }

        RateLimiter::clear($throttleKey);
        $user = Auth::user();

        if (! $user->is_active) {
            Auth::logout();
            $this->errorMessage = 'Akun Anda telah dinonaktifkan.';
            return;
        }

        if ($user->role === 'penjual' && ! $user->is_verified) {
            Auth::logout();
            if ($user->rejection_reason) {
                $this->errorMessage = 'Pendaftaran Anda ditolak. Alasan: ' . $user->rejection_reason;
            } else {
                $this->errorMessage = 'Akun dalam proses verifikasi admin.';
            }
            return;
        }

        session()->regenerate();
        \App\Services\AuditLogger::log('user.login', "User #{$user->id} login");

        $this->redirect(match ($user->role) {
            'admin' => route('admin.dashboard'),
            'penjual' => route('seller.dashboard'),
            default => route('dashboard'),
        }, navigate: true);
    }

    public function render()
    {
        return view('livewire.auth.login-form');
    }
}