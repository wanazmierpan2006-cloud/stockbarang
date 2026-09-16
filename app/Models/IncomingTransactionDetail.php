<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncomingTransactionDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'incoming_transaction_id',
        'item_id',
        'jumlah',
    ];

    public function transaction()
    {
        return $this->belongsTo(IncomingTransaction::class, 'incoming_transaction_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
