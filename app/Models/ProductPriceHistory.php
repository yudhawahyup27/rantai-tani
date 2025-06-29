<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPriceHistory extends Model
{
    protected $fillable = [
        'product_id', 'old_price_sell', 'new_price_sell',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
