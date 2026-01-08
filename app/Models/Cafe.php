<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cafe extends Model
{
    /** @use HasFactory<\Database\Factories\CafeFactory> */
    use HasFactory;

    protected $guarded = [];
    public function cafeDetail()
    {
        return $this->hasOne(CafeDetail::class);
    }
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function menus()
    {
        return $this->hasMany(Menu::class);
    }

    public function tables()
    {
        return $this->hasMany(Table::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function averageRating()
    {
        return $this->reviews()->avg('rating');
    }


}
