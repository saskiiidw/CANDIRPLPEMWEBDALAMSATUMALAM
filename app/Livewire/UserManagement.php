<?php

namespace App\Livewire;

use App\Livewire\Concerns\AuthorizesRole;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class UserManagement extends Component
{
    use AuthorizesRole, WithPagination;

    public string $search = '';

    public function mount(): void
    {
        $this->authorizeRole('admin');
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function toggleActive(int $userId): void
    {
        $user = User::findOrFail($userId);
        if ($user->role === 'admin') return;

        $user->update(['is_active' => ! $user->is_active]);
        \App\Services\AuditLogger::log('user.status.toggled', "User #{$user->id} is_active = {$user->is_active}");
    }

    public function render()
    {
        $users = User::where('role', '!=', 'admin')
            ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->paginate(10);

        return view('livewire.user-management', ['users' => $users]);
    }
}