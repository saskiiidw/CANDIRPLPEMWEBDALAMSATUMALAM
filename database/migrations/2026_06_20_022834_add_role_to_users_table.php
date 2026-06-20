<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('mahasiswa')->after('email'); // mahasiswa|penjual|admin
            $table->string('store_name')->nullable()->after('role'); // khusus penjual
            $table->boolean('is_verified')->default(false)->after('store_name'); // verifikasi admin utk penjual
            $table->boolean('is_active')->default(true)->after('is_verified');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'store_name', 'is_verified', 'is_active']);
        });
    }
};