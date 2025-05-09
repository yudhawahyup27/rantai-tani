<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $guarded = ['id'];

    protected $casts = [
        'id_tossa' => 'integer',
        'id_shift' => 'integer',
        'id_role' => 'integer',
    ];

    protected $hidden = ['password', 'remember_token'];

    /**
     * Relasi ke tabel Role menggunakan Spatie Permission
     */
    public function roles()
    {
        return $this->belongsTo(Role::class, 'id_role', 'id');
    }
    public function role()
    {
        return $this->roles();
    }

    public function hasRole($roleName)
{
    return $this->roles && $this->roles->role === $roleName;
    dd($roleName);
}
    /**
     * Relasi ke tabel Tossa
     */
    public function userTossa()
    {
        return $this->belongsTo(Tossa::class, 'id_tossa');
    }

    /**
     * Relasi ke tabel Shift
     */
    public function workShift()
    {
        return $this->belongsTo(Shift::class, 'id_shift');
    }
}
