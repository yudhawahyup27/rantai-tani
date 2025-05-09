<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mastersaham extends Model
{
    use HasFactory;

    protected $fillable = [
        'tossa_id',
        'totallot',
        'sahamtersedia',
        'sahamterjual',
        'persentase',
        'harga',
        'total',
    ];

    public function tossa()
    {
        return $this->belongsTo(Tossa::class, 'tossa_id', 'id');
     }


}
