<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoucherUser extends Model
{
    /** @use HasFactory<\Database\Factories\VoucherUserFactory> */
    use HasFactory;

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function voucher() {
        return $this->belongsTo(Voucher::class);
    }
}
