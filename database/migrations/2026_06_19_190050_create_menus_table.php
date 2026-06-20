<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_id')->constrained('users')->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('category')->default('makanan_berat'); // makanan_berat|makanan_ringan|minuman
            $table->unsignedBigInteger('price');
            $table->unsignedInteger('stock')->default(0);
            $table->unsignedInteger('cooking_time_minutes')->default(10);
            $table->boolean('is_active')->default(true);
            $table->string('photo_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};