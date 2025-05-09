<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;


    protected $table = 'products'; // Pastikan sesuai dengan nama tabel di database

    protected $fillable = ["name", "price", "image", "laba", "id_satuan", "category", "price_sell"];

    public function satuan()
    {
        return $this->belongsTo(Satuan::class, 'id_satuan');
    }

}
