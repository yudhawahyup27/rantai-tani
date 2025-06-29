<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class newStock extends Model
{
    use HasFactory;
     protected $fillable = ['stock_id', 'quantity_added'];

    public function stock()
    {
        return $this->belongsTo(Stocks::class, 'stock_id');
    }
}
