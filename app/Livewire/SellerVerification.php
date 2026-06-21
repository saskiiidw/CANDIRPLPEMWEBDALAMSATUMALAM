<?php

namespace App\Livewire;

use App\Livewire\Concerns\AuthorizesRole;
use App\Models\User;
use App\Services\AuditLogger;
use Livewire\Component;
use Livewire\WithPagination;

class SellerVerification extends Component
{
    use AuthorizesRole, WithPagination;

    public string $search = '';
    public ?int $selectedSellerId = null;

    public function mount(): void
    {
        $this->authorizeRole('admin');
        $this->selectFirstSeller();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatedSearch(): void
    {
        $this->selectFirstSeller();
    }

    private function selectFirstSeller(): void
    {
        $firstSeller = User::where('role', 'penjual')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', "%{$this->search}%")
                      ->orWhere('store_name', 'like', "%{$this->search}%");
                });
            })
            ->first();

        $this->selectedSellerId = $firstSeller ? $firstSeller->id : null;
    }

    public function selectSeller(int $sellerId): void
    {
        $this->selectedSellerId = $sellerId;
    }

    public function approve(int $userId): void
    {
        $seller = User::where('role', 'penjual')->findOrFail($userId);
        $seller->update(['is_verified' => true]);
        
        AuditLogger::log('seller.verified', "Penjual #{$seller->id} ({$seller->name}) disetujui admin");
        
        session()->flash('message', 'Seller successfully approved!');
        
        // Auto-select another seller
        if ($this->selectedSellerId === $userId) {
            $this->selectFirstSeller();
        }
    }

    public function reject(int $userId): void
    {
        $seller = User::where('role', 'penjual')->findOrFail($userId);
        
        AuditLogger::log('seller.rejected', "Penjual #{$seller->id} ({$seller->name}) ditolak admin");
        
        $seller->delete();
        
        session()->flash('message', 'Seller request rejected and deleted.');
        
        // Auto-select another seller
        if ($this->selectedSellerId === $userId) {
            $this->selectFirstSeller();
        }
    }

    public function render()
    {
        $sellers = User::where('role', 'penjual')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', "%{$this->search}%")
                      ->orWhere('store_name', 'like', "%{$this->search}%");
                });
            })
            ->orderBy('is_verified', 'asc') // Show unverified first
            ->orderBy('created_at', 'desc')
            ->paginate(8);

        $selectedSeller = $this->selectedSellerId 
            ? User::where('role', 'penjual')->find($this->selectedSellerId) 
            : null;

        return view('livewire.seller-verification', [
            'sellers' => $sellers,
            'selectedSeller' => $selectedSeller,
        ])->layout('layouts.admin');
    }
}