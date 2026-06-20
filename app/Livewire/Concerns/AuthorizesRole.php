<?php

namespace App\Livewire\Concerns;

trait AuthorizesRole
{
    protected function authorizeRole(string ...$roles): void
    {
        $user = auth()->user();

        if (! $user || ! in_array($user->role, $roles, true)) {
            abort(403, 'Anda tidak memiliki akses ke fitur ini.');
        }
    }
}