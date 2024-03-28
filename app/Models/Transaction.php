<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'Sender_id',
        'resever_id',
        'wallet_id',
    ];

    public function wallet(){
        return $this->belongsTo(Wallet::class);
    }
}
