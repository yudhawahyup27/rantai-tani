<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyRevenue extends Model
{
    use HasFactory;
     protected $fillable = [
      'tossa_id', 'date', 'shift', 'product_id',
    'stock_start', 'sold_quantity', 'stock_end',
    'start_value', 'sold_value', 'end_value', 'revenue', 'id_user'
    ];

    public function product()
{
    return $this->belongsTo(Product::class);
}

}
