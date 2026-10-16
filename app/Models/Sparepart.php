<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sparepart extends Model
{
    protected $fillable = ['nama_sparepart', 'kategori', 'harga', 'stok', 'gambar'];

    protected function casts(): array
    {
        return ['harga' => 'integer', 'stok' => 'integer'];
    }
}
