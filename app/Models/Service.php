<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    protected $fillable = ['nama_jasa', 'kategori', 'harga', 'estimasi_waktu', 'status'];

    protected function casts(): array
    {
        return ['harga' => 'integer', 'status' => 'boolean'];
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
