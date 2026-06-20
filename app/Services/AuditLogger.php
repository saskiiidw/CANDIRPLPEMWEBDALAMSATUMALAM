<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class AuditLogger
{
    public static function log(string $action, ?string $description = null): void
    {
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'description' => $description,
        ]);
    }
}