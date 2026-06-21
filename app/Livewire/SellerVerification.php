<?php

namespace App\Livewire;

use App\Livewire\Concerns\AuthorizesRole;
use App\Models\User;
use Livewire\Component;

class SellerVerification extends Component
{
    use AuthorizesRole;

    public $pendingSellers;
    public string $rejectionReason = '';

    public function mount(): void
    {
        $this->authorizeRole('admin');
        $this->loadPending();
    }

    public function loadPending(): void
    {
        $this->pendingSellers = User::where('role', 'penjual')
            ->where('is_verified', false)
            ->whereNull('rejection_reason')
            ->get();
    }

    public function approve(int $userId): void
    {
        $seller = User::where('role', 'penjual')->findOrFail($userId);
        $seller->update(['is_verified' => true, 'rejection_reason' => null]);
        \App\Services\AuditLogger::log('seller.verified', "Penjual #{$seller->id} disetujui admin");
        $this->loadPending();
    }

    public function reject(int $userId, string $reason): void
    {
        $seller = User::where('role', 'penjual')->findOrFail($userId);

        $seller->update([
            'rejection_reason' => $reason,
            'is_active' => false
        ]);

        \App\Services\AuditLogger::log('seller.rejected', "Penjual #{$seller->id} ditolak. Alasan: {$reason}");

        $this->reset('rejectionReason');
        $this->loadPending();
    }

    public function render()
    {
        return view('livewire.seller-verification');
    }
}