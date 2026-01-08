<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    /** @use HasFactory<\Database\Factories\TableFactory> */
    use HasFactory;

    protected $guarded = [];
    public function cafe()
    {
        return $this->belongsTo(Cafe::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function isAvailable($date, $time)
    {
        return !$this->bookings()
            ->whereDate('arrival_time', $date)
            ->whereTime('arrival_time', $time)
            ->whereNotIn('status', ['cancelled', 'payment_rejected'])
            ->exists();
    }
}
