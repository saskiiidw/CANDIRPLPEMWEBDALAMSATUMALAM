<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class ProfileEditor extends Component
{
    use WithFileUploads;

    public string $name = '';
    public string $phone = '';
    public string $store_name = '';
    public $photo;

    public function mount(): void
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->phone = $user->phone ?? '';
        $this->store_name = $user->store_name ?? '';
    }

    protected function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:100',
            'phone' => 'nullable|string|max:20',
            'photo' => 'nullable|image|max:2048',
        ];

        if (Auth::user()->role === 'penjual') {
            $rules['store_name'] = 'required|string|max:100';
        }

        return $rules;
    }

    public function save(): void
    {
        $validated = $this->validate();
        $user = Auth::user();

        if ($this->photo) {
            $user->photo_path = $this->photo->store('profile-photos', 'public');
        }

        $user->name = $validated['name'];
        $user->phone = $validated['phone'];

        if ($user->role === 'penjual') {
            $user->store_name = $validated['store_name'];
        }

        $user->save();
        \App\Services\AuditLogger::log('profile.updated', "User #{$user->id} memperbarui profil");

        $this->dispatch('profile-updated');
    }

    public function render()
    {
        return view('livewire.profile-editor');
    }
}