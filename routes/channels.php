<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('seller.{sellerId}', function ($user, $sellerId) {
    return (int) $user->id === (int) $sellerId && $user->role === 'penjual';
});

Broadcast::channel('order.{orderId}', function ($user, $orderId) {
    $order = \App\Models\Order::find($orderId);
    return $order && (int) $user->id === (int) $order->buyer_id;
});