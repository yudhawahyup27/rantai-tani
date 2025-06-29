<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    use HasFactory;

   protected $fillable = [
    'id_user',
    'tossa_id',
    'labaSayur',
    'labaBuah',
    'labaGaringan',
    'bonus',
    'passiveIncome', // konsisten dengan lowercase
    'totalLabaBahanBaku',
    'totalLabaKeseluruhan',
];

    /**
     * Relasi ke Tossa
     */

      public function user (){
        return $this->belongsTo(User::class,"id_user");
    }
    public function tossa()
    {
        return $this->belongsTo(Tossa::class);
    }
}
