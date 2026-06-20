<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_id', 'name', 'description', 'category',
        'price', 'stock', 'cooking_time_minutes', 'is_active', 'photo_path',
    ];

    public function seller()
    {
        return $this->belongsTo(\App\Models\User::class, 'seller_id');
    }

    public function isAvailable(int $qty = 1): bool
    {
        return $this->is_active && $this->stock >= $qty;
    }
}