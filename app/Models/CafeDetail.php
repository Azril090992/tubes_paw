<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CafeDetail extends Model
{
    /** @use HasFactory<\Database\Factories\CafeDetailFactory> */
    use HasFactory;
    protected $guarded = [];

    public function cafe()
    {
        return $this->belongsTo(Cafe::class);
    }
}
