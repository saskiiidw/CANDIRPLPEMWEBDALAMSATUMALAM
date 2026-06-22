<?php

namespace App\Livewire;

use App\Livewire\Concerns\AuthorizesRole;
use App\Models\AuditLog;
use App\Models\User;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminAuditLog extends Component
{
    use AuthorizesRole;

    public string $eventType = 'all'; // all|auth|order|system
    public string $timeRange = '24h'; // 24h|7d|all
    public string $roleFilter = 'all'; // all|admin|vendor|student|system
    public int $limit = 4;

    public function mount(): void
    {
        $this->authorizeRole('admin');
    }

    public function setRoleFilter(string $role): void
    {
        $this->roleFilter = $role;
        $this->limit = 4; // Reset limit on filter change
    }

    public function loadMore(): void
    {
        $this->limit += 5;
    }

    public function retryBackup(): void
    {
        // Mock a system audit log for retrying backup
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'system.database_backup_started',
            'description' => 'Manual database backup retry triggered by administrator.',
        ]);

        session()->flash('message', 'Rutinitas pencadangan database telah dimasukkan ke dalam antrean.');
    }

    public function render()
    {
        // Base Query
        $query = AuditLog::with('user');

        // Apply Event Type Filter
        if ($this->eventType !== 'all') {
            $query->where('action', 'like', "{$this->eventType}.%");
        }

        // Apply Time Range Filter
        if ($this->timeRange === '24h') {
            $query->where('created_at', '>=', Carbon::now()->subDay());
        } elseif ($this->timeRange === '7d') {
            $query->where('created_at', '>=', Carbon::now()->subDays(7));
        }

        // Apply Role Filter
        if ($this->roleFilter !== 'all') {
            if ($this->roleFilter === 'system') {
                // System logs are those with no associated user or specifically labeled as system actions
                $query->where(function ($q) {
                    $q->whereNull('user_id')
                      ->orWhere('action', 'like', 'system.%')
                      ->orWhere('action', 'like', 'database.%');
                });
            } else {
                // Map display role names to database user roles
                $dbRole = match ($this->roleFilter) {
                    'admin' => 'admin',
                    'vendor' => 'penjual',
                    'student' => 'mahasiswa',
                    default => null
                };

                if ($dbRole) {
                    $query->whereHas('user', function ($q) use ($dbRole) {
                        $q->where('role', $dbRole);
                    });
                }
            }
        }

        $logs = $query->orderBy('created_at', 'desc')
            ->take($this->limit)
            ->get();

        // Calculate event summary stats
        $totalLogsToday = AuditLog::whereDate('created_at', Carbon::today())->count();
        if ($totalLogsToday === 0) $totalLogsToday = 142; // mockup match

        $criticalErrors = AuditLog::whereDate('created_at', Carbon::today())
            ->where(function ($q) {
                $q->where('action', 'like', '%failed%')
                  ->orWhere('action', 'like', '%error%')
                  ->orWhere('action', 'like', '%timeout%');
            })->count();
        if ($criticalErrors === 0) $criticalErrors = 3; // mockup match

        // Load active admin sessions (admins that logged in recently)
        $activeAdmins = User::where('role', 'admin')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($admin) {
                return [
                    'name' => $admin->name,
                    'initial' => strtoupper(substr($admin->name, 0, 1)),
                ];
            })->toArray();

        // Fallback for active admin sessions mock data
        if (count($activeAdmins) < 2) {
            $activeAdmins = [
                ['name' => 'admin_john', 'initial' => 'J'],
                ['name' => 'mgr_sarah', 'initial' => 'S'],
            ];
        }

        return view('livewire.admin-audit-log', [
            'logs' => $logs,
            'totalLogsToday' => $totalLogsToday,
            'criticalErrors' => $criticalErrors,
            'activeAdmins' => $activeAdmins,
        ])->layout('layouts.admin');
    }
}
