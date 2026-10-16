<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class SparepartOrderItem extends Model
{
    protected $fillable = ['sparepart_order_id','sparepart_id','nama_sparepart','harga_satuan','jumlah','subtotal'];
    protected function casts(): array { return ['harga_satuan'=>'integer','jumlah'=>'integer','subtotal'=>'integer']; }
    public function order(): BelongsTo { return $this->belongsTo(SparepartOrder::class, 'sparepart_order_id'); }
    public function sparepart(): BelongsTo { return $this->belongsTo(Sparepart::class); }
}
