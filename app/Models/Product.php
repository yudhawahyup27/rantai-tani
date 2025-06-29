<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        "name",
        "price",
        "image",
        "laba",
        "id_satuan",
        "category",
        "jenis",
        "pemilik",
        "price_sell"
    ];

    // Konstanta untuk jenis produk (sesuaikan dengan data di database)
    const JENIS_BELI = 'beli';
    const JENIS_TITIPAN = 'titipan'; // Pastikan ini sesuai dengan database

    public function satuan()
    {
        return $this->belongsTo(Satuan::class, 'id_satuan');
    }

    // Scope untuk filtering
    public function scopeBeli($query)
    {
        return $query->where('jenis', self::JENIS_BELI);
    }

    public function scopeTitipan($query)
    {
        return $query->where('jenis', self::JENIS_TITIPAN);
    }
}
