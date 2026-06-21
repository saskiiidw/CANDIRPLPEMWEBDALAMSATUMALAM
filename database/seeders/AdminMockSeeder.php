<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\AuditLog;
use App\Models\Order;
use App\Models\Menu;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AdminMockSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Admin Users
        $eleanor = User::updateOrCreate(
            ['email' => 'eleanor.v@smartcanteen.edu'],
            [
                'name' => 'Eleanor Vance',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '+1 (555) 321-7654',
                'is_verified' => true,
                'is_active' => true,
                'created_at' => Carbon::parse('2023-09-01 08:00:00'),
            ]
        );

        $john = User::updateOrCreate(
            ['email' => 'john@unsoed.ac.id'],
            [
                'name' => 'admin_john',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_verified' => true,
                'is_active' => true,
            ]
        );

        $sarah = User::updateOrCreate(
            ['email' => 'sarah@unsoed.ac.id'],
            [
                'name' => 'mgr_sarah',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_verified' => true,
                'is_active' => true,
            ]
        );

        // 2. Seed Sellers (penjual)
        $maria = User::updateOrCreate(
            ['email' => 'maria.g@example.com'],
            [
                'name' => 'Maria Gonzales',
                'password' => Hash::make('password'),
                'role' => 'penjual',
                'store_name' => "Mama Maria's Eatery",
                'phone' => '+1 (555) 123-4567',
                'is_verified' => false,
                'is_active' => true,
                'created_at' => Carbon::parse('2023-10-24 10:00:00'),
            ]
        );

        $johnSmith = User::updateOrCreate(
            ['email' => 'john.s@example.com'],
            [
                'name' => 'John Smith',
                'password' => Hash::make('password'),
                'role' => 'penjual',
                'store_name' => 'Campus Bites',
                'phone' => '+1 (555) 987-6543',
                'is_verified' => false,
                'is_active' => true,
                'created_at' => Carbon::parse('2023-10-23 11:30:00'),
            ]
        );

        $sarahLee = User::updateOrCreate(
            ['email' => 'sarah.l@example.com'],
            [
                'name' => 'Sarah Lee',
                'password' => Hash::make('password'),
                'role' => 'penjual',
                'store_name' => 'Green Bowl Salads',
                'phone' => '+1 (555) 246-8101',
                'is_verified' => true,
                'is_active' => true,
                'created_at' => Carbon::parse('2023-10-20 09:15:00'),
            ]
        );

        $vendor03 = User::updateOrCreate(
            ['email' => 'vendor03@kantin.unsoed.ac.id'],
            [
                'name' => 'vendor_03',
                'password' => Hash::make('password'),
                'role' => 'penjual',
                'store_name' => 'Warung Pojok',
                'phone' => '+1 (555) 999-8888',
                'is_verified' => true,
                'is_active' => true,
            ]
        );

        // 3. Seed Students (mahasiswa)
        $alex = User::updateOrCreate(
            ['email' => 'alex.m@student.edu'],
            [
                'name' => 'Alex Mercer',
                'password' => Hash::make('password'),
                'role' => 'mahasiswa',
                'phone' => '+1 (555) 432-1098',
                'student_id' => 'S1029384',
                'faculty' => 'Engineering',
                'is_verified' => true,
                'is_active' => true,
                'email_verified_at' => Carbon::parse('2023-09-01 00:00:00'),
                'created_at' => Carbon::parse('2023-09-01 09:00:00'),
            ]
        );

        $sam = User::updateOrCreate(
            ['email' => 'sam.j@student.edu'],
            [
                'name' => 'Sam Jones',
                'password' => Hash::make('password'),
                'role' => 'mahasiswa',
                'student_id' => 'S9928374',
                'faculty' => 'Arts',
                'is_verified' => true,
                'is_active' => true,
                'email_verified_at' => null, // Pending status
                'created_at' => Carbon::parse('2023-09-05 10:15:00'),
            ]
        );

        $jamie = User::updateOrCreate(
            ['email' => 'j.rivera@student.edu'],
            [
                'name' => 'Jamie Rivera',
                'password' => Hash::make('password'),
                'role' => 'mahasiswa',
                'student_id' => 'S8837465',
                'faculty' => 'Science',
                'is_verified' => true,
                'is_active' => false, // Inactive status
                'email_verified_at' => Carbon::parse('2023-09-10 00:00:00'),
                'created_at' => Carbon::parse('2023-09-10 14:20:00'),
            ]
        );

        // 4. Seed Audit Logs
        AuditLog::query()->delete();

        AuditLog::create([
            'user_id' => $john->id,
            'action' => 'auth.login',
            'description' => 'User admin_john logged in from IP 192.168.1.45.',
            'created_at' => Carbon::today()->setHour(10)->setMinute(45)->setSecond(0),
        ]);

        AuditLog::create([
            'user_id' => $sarah->id,
            'action' => 'order.refunded',
            'description' => 'Order #ORD-9921 refunded by mgr_sarah. Reason: Out of stock.',
            'created_at' => Carbon::today()->setHour(9)->setMinute(12)->setSecond(0),
        ]);

        AuditLog::create([
            'user_id' => null,
            'action' => 'system.database_backup_failed',
            'description' => "Automated daily backup encountered a timeout error on table 'transaction_logs'.",
            'created_at' => Carbon::today()->setHour(3)->setMinute(0)->setSecond(0),
        ]);

        AuditLog::create([
            'user_id' => null,
            'action' => 'auth.failed',
            'description' => 'Invalid credentials supplied for user vendor_03 from IP 203.0.113.42.',
            'created_at' => Carbon::yesterday()->setHour(23)->setMinute(23)->setSecond(0),
        ]);

        // Add some more mock logs to make it look realistic
        AuditLog::create([
            'user_id' => $eleanor->id,
            'action' => 'user.status.toggled',
            'description' => 'User #3 status toggled to inactive.',
            'created_at' => Carbon::yesterday()->setHour(18)->setMinute(45)->setSecond(0),
        ]);

        AuditLog::create([
            'user_id' => $eleanor->id,
            'action' => 'seller.verified',
            'description' => 'Penjual #6 disetujui admin.',
            'created_at' => Carbon::yesterday()->setHour(15)->setMinute(30)->setSecond(0),
        ]);
    }
}
