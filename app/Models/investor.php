<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Investor extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'tossa_id',
        'perlot',
        'Deviden', // Jika perlu menyimpan nominal dividen
        'total', // Jika perlu menyimpan nominal dividen
    ];

    public function user()
{
    return $this->belongsTo(User::class);
}
public function tossa()
{
    return $this->belongsTo(Mastersaham::class, 'tossa_id');
}

public function tossaName()
{
    return $this->tossa?->tossa?->name ?? '-';
}
}
