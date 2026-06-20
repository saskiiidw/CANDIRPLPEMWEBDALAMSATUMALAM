<?php

namespace App\Livewire;

use App\Livewire\Concerns\AuthorizesRole;
use App\Models\User;
use Livewire\Component;

class SellerVerification extends Component
{
    use AuthorizesRole;

    public $pendingSellers;

    public function mount(): void
    {
        $this->authorizeRole('admin');
        $this->loadPending();
    }

    public function loadPending(): void
    {
        $this->pendingSellers = User::where('role', 'penjual')
            ->where('is_verified', false)
            ->get();
    }

    public function approve(int $userId): void
    {
        $seller = User::where('role', 'penjual')->findOrFail($userId);
        $seller->update(['is_verified' => true]);
        \App\Services\AuditLogger::log('seller.verified', "Penjual #{$seller->id} disetujui admin");
        $this->loadPending();
    }

    public function reject(int $userId): void
    {
        $seller = User::where('role', 'penjual')->findOrFail($userId);
        \App\Services\AuditLogger::log('seller.rejected', "Penjual #{$seller->id} ditolak admin");
        $seller->delete();
        $this->loadPending();
    }

    public function render()
    {
        return view('livewire.seller-verification');
    }
}