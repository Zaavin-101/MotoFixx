<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class OrderItem extends Model {
    protected $fillable = ['order_id','package_id','sparepart_id','item_name','quantity','unit_price','line_total'];
    protected $casts = ['quantity'=>'integer','unit_price'=>'integer','line_total'=>'integer'];
    public function order(): BelongsTo { return $this->belongsTo(Order::class); }
    public function package(): BelongsTo { return $this->belongsTo(ServicePackage::class, 'package_id'); }
    public function sparepart(): BelongsTo { return $this->belongsTo(Sparepart::class); }
}
