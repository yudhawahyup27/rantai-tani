<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MitraStock extends Model
{
    protected $guarded = ['id'];
       public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
