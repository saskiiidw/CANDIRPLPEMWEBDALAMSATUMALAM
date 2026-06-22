<?php

namespace App\Livewire;

use App\Livewire\Concerns\AuthorizesRole;
use App\Models\User;
use App\Services\AuditLogger;
use Livewire\Component;
use Livewire\WithPagination;

class UserManagement extends Component
{
    use AuthorizesRole, WithPagination;

    public string $search = '';
    public string $tab = 'student'; // student|seller
    public ?int $selectedUserId = null;

    public function mount(): void
    {
        $this->authorizeRole('admin');
        $this->selectFirstUser();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatedSearch(): void
    {
        $this->selectFirstUser();
    }

    public function setTab(string $newTab): void
    {
        if (in_array($newTab, ['student', 'seller'])) {
            $this->tab = $newTab;
            $this->resetPage();
            $this->selectFirstUser();
        }
    }

    private function selectFirstUser(): void
    {
        $role = $this->tab === 'student' ? 'mahasiswa' : 'penjual';
        
        $firstUser = User::where('role', $role)
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', "%{$this->search}%")
                      ->orWhere('email', 'like', "%{$this->search}%")
                      ->orWhere('student_id', 'like', "%{$this->search}%")
                      ->orWhere('store_name', 'like', "%{$this->search}%");
                });
            })
            ->first();

        $this->selectedUserId = $firstUser ? $firstUser->id : null;
    }

    public function selectUser(int $userId): void
    {
        $this->selectedUserId = $userId;
    }

    public function activate(int $userId): void
    {
        $user = User::findOrFail($userId);
        $user->update(['is_active' => true]);
        
        AuditLogger::log('user.status.toggled', "User #{$user->id} ({$user->name}) status diaktifkan");
        
        session()->flash('message', "Akun pengguna {$user->name} berhasil diaktifkan.");
    }

    public function deactivate(int $userId): void
    {
        $user = User::findOrFail($userId);
        if ($user->role === 'admin') return;
        
        $user->update(['is_active' => false]);
        
        AuditLogger::log('user.status.toggled', "User #{$user->id} ({$user->name}) status dinonaktifkan");
        
        session()->flash('message', "Akun pengguna {$user->name} berhasil dinonaktifkan.");
    }

    public function render()
    {
        $role = $this->tab === 'student' ? 'mahasiswa' : 'penjual';

        $users = User::where('role', $role)
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', "%{$this->search}%")
                      ->orWhere('email', 'like', "%{$this->search}%")
                      ->orWhere('student_id', 'like', "%{$this->search}%")
                      ->orWhere('store_name', 'like', "%{$this->search}%");
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(6);

        $selectedUser = $this->selectedUserId 
            ? User::find($this->selectedUserId) 
            : null;

        return view('livewire.user-management', [
            'users' => $users,
            'selectedUser' => $selectedUser,
        ])->layout('layouts.admin');
    }
}