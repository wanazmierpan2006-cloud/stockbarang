<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockAdjustment extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_penyesuaian',
        'item_id',
        'user_id',
        'stok_sebelumnya',
        'stok_sesudah',
        'selisih',
        'tipe',
        'alasan',
        'tanggal',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'datetime',
        ];
    }

    public function item()
    {
        return $this->belongsTo(Item::class)->withTrashed();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
