<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutgoingTransactionDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'outgoing_transaction_id',
        'item_id',
        'jumlah',
    ];

    public function transaction()
    {
        return $this->belongsTo(OutgoingTransaction::class, 'outgoing_transaction_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
