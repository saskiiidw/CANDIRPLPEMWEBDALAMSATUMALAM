<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.guest')]
class RegisterForm extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $role = 'mahasiswa'; // mahasiswa|penjual
    public string $store_name = '';

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'role' => 'required|in:mahasiswa,penjual',
            'store_name' => 'required_if:role,penjual|nullable|string|max:100',
        ];
    }

    public function register(): void
    {
        $validated = $this->validate();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'store_name' => $validated['role'] === 'penjual' ? $validated['store_name'] : null,
            'is_verified' => $validated['role'] === 'penjual' ? false : true,
            'is_active' => true,
        ]);

        \App\Services\AuditLogger::log('user.registered', "User #{$user->id} ({$user->role}) mendaftar");

        Auth::login($user);

        $this->redirect(
            $user->role === 'penjual' ? route('seller.pending') : route('dashboard'),
            navigate: true
        );
    }

    public function render()
    {
        return view('livewire.auth.register-form');
    }
}