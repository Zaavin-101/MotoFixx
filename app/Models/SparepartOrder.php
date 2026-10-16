<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class SparepartOrder extends Model
{
    protected $fillable = ['user_id','kode_pesanan','payment_method','status_pembayaran','status_pesanan','total_harga'];
    protected function casts(): array { return ['total_harga' => 'integer']; }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function items(): HasMany { return $this->hasMany(SparepartOrderItem::class); }
}
