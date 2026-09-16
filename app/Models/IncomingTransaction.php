<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncomingTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_transaksi',
        'supplier_id',
        'user_id',
        'tanggal',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'datetime',
        ];
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function details()
    {
        return $this->hasMany(IncomingTransactionDetail::class);
    }

    public function getTotalQuantityAttribute(): int
    {
        return $this->details->sum('jumlah');
    }
}
