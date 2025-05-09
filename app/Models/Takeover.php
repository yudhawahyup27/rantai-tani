<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Takeover extends Model
{
    use HasFactory;

    protected $fillable = [
        'investor_id',
        'from_user_id',
        'to_user_id',
        'tossa_id',
        'perlot',
        'harga_takeover',
        'total',
    ];

    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function toUser()
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }

    public function investor()
    {
        return $this->belongsTo(Investor::class, 'investor_id');
    }

    public function tossa()
    {
        return $this->belongsTo(Tossa::class, 'tossa_id');
    }

    public function tossaName()
    {
        // Perbaikan method tossaName agar lebih aman
        return $this->tossa ? $this->tossa->name : '-';
    }
}
