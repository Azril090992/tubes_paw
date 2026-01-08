<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [
        'user_id',
        'menu_id',
        'quantity',
        'notes'
    ];

    protected $casts = [
        'quantity' => 'integer',
    ];

    // Relationship: Cart belongs to User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship: Cart belongs to Menu
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    // Accessor: Get subtotal for this cart item
    public function getSubtotalAttribute()
    {
        return $this->quantity * $this->menu->price;
    }

    // Scope: Get cart items for specific user
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Scope: Get cart items for specific cafe
    public function scopeForCafe($query, $cafeId)
    {
        return $query->whereHas('menu', function ($q) use ($cafeId) {
            $q->where('cafe_id', $cafeId);
        });
    }
}