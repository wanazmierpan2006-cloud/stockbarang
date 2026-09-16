<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'alamat',
        'telepon',
        'email',
    ];

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function incomingItems()
    {
        return $this->hasMany(IncomingItem::class);
    }
}
