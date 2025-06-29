<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanKeuangan extends Model
{
    use HasFactory;
    protected $table = 'laporan_keuangans';

    protected $fillable = [
        'tossa_id',
        'id_Pic',
        'daganganBaru',
        'gajikaryawan',
        'pengeluaran',
        'daganganlakuterjual',
        'labakotor',
        'ravenue',
        'margin',
        'grosMargin',
        'sewaTossa',
        'labaBersih',
        'labaDibawa',
        'rangelabakotor',
        'note',
    ];

    public function tossa()
{
    return $this->belongsTo(Tossa::class);
}

public function pic()
{
    return $this->belongsTo(User::class, 'id_Pic');
}
}
