<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\NewStock; // Perbaikan: Gunakan PascalCase yang konsisten

class Stocks extends Model
{
    use HasFactory;

    protected $fillable = [
        'tossa_id',
        'product_id',
        'quantity',
        'quantity_new',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function tossa()
    {
        return $this->belongsTo(Tossa::class, 'tossa_id');
    }

    public function newStock()
    {
        return $this->hasMany(NewStock::class, 'stock_id'); // Pastikan menggunakan NewStock (PascalCase)
    }
}
