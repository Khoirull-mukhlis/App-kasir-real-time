<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class Transaksi extends Model
{
    use HasFactory;
    protected $table = 'transaksis';

    // Kolom yang dapat diisi (fillable)
    protected $fillable = [
        'total_harga',
        'bayar',
        'kembalian',
        'detail_transaksi',
    ];

    // Kolom yang akan otomatis di-cast ke tipe tertentu
    protected $casts = [
        'detail_transaksi' => 'array',
    ];

    /**
     * Accessor untuk mendapatkan detail transaksi dalam format array.
     */
    public function getDetailTransaksiAttribute($value)
    {
        return json_decode($value, true); // Decode JSON saat diakses
    }

    /**
     * Mutator untuk menyimpan detail transaksi dalam format JSON.
     */
    public function setDetailTransaksiAttribute($value)
    {
        $this->attributes['detail_transaksi'] = json_encode($value); // Encode array menjadi JSON saat disimpan
        $this->attributes['waktu_transaksi']= Carbon::now();
    }
}

