<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Payment extends Model {
    protected $fillable = ['order_id','provider','method','transaction_id','snap_token','status','amount','raw_response'];
    protected $casts = ['amount'=>'integer','raw_response'=>'array'];
    public function order(): BelongsTo { return $this->belongsTo(Order::class); }
}
