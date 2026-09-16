<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_barang',
        'barcode',
        'nama_barang',
        'category_id',
        'supplier_id',
        'satuan',
        'stok',
        'minimum_stok',
        'keterangan',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function incomingDetails()
    {
        return $this->hasMany(IncomingTransactionDetail::class);
    }

    public function outgoingDetails()
    {
        return $this->hasMany(OutgoingTransactionDetail::class);
    }

    public function isLowStock(): bool
    {
        return $this->stok <= $this->minimum_stok;
    }
}
