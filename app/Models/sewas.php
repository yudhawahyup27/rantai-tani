<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sewas extends Model
{
    use HasFactory;

    protected $table="sewas";

    protected $fillable = ['user_id',"hargaSewa"];


    // Tossa id

    public function tossas ()
    {
        return $this->belongsTo(Tossa::class,"tossa_id","id");
    }

    public function user (){
        return $this->belongsTo(User::class,"user_id");
    }
}
