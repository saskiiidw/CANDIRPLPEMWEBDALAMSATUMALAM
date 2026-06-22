<?php

namespace App\Livewire;

use App\Livewire\Concerns\AuthorizesRole;
use App\Models\User;
use App\Services\AuditLogger;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AdminProfile extends Component
{
    use AuthorizesRole;

    public string $firstName = '';
    public string $lastName = '';
    public string $email = '';
    public bool $emailNotifications = true;
    public bool $twoFactor = false;

    public function mount(): void
    {
        $this->authorizeRole('admin');
        
        $user = Auth::user();
        
        // Split name into first and last name
        $nameParts = explode(' ', $user->name, 2);
        $this->firstName = $nameParts[0] ?? '';
        $this->lastName = $nameParts[1] ?? '';
        $this->email = $user->email;
    }

    public function save(): void
    {
        $this->validate([
            'firstName' => 'required|string|max:50',
            'lastName' => 'nullable|string|max:50',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
        ]);

        $user = Auth::user();
        $fullName = trim($this->firstName . ' ' . $this->lastName);
        
        $user->update([
            'name' => $fullName,
            'email' => $this->email,
        ]);

        AuditLogger::log('user.profile.updated', "Admin profile updated for #{$user->id} ({$fullName})");

        session()->flash('message', 'Profil berhasil diperbarui!');
    }

    public function logout(): void
    {
        $user = Auth::user();
        
        AuditLogger::log('user.logout', "User #{$user->id} logged out from profile page");
        
        Auth::logout();
        
        session()->invalidate();
        session()->regenerateToken();

        $this->redirect('/', navigate: true);
    }

    public function toggleEmailNotifications(): void
    {
        $this->emailNotifications = !$this->emailNotifications;
        session()->flash('pref_message', 'Preferensi sistem diperbarui.');
    }

    public function enableTwoFactor(): void
    {
        $this->twoFactor = true;
        session()->flash('pref_message', 'Konfigurasi Autentikasi Dua Faktor dimulai.');
    }

    public function render()
    {
        $user = Auth::user();
        
        return view('livewire.admin-profile', [
            'user' => $user,
        ])->layout('layouts.admin');
    }
}
