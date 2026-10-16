<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Booking extends Model
{
    protected $fillable = [
        'user_id', 'kode_booking', 'nama_pelanggan', 'merek_motor', 'tipe_motor', 'nomor_plat',
        'keluhan', 'tanggal', 'jam', 'service_id', 'mekanik_name',
        'status_pengerjaan', 'status_pembayaran', 'total_harga', 'payment_method',
    ];

    protected function casts(): array
    {
        return ['tanggal' => 'date', 'total_harga' => 'integer'];
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(BookingStatusHistory::class)->orderBy('created_at');
    }
}
