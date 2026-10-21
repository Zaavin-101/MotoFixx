<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class MechanicLocation extends Model {
    protected $fillable = ['order_id','latitude','longitude','recorded_at'];
    protected $casts = ['latitude'=>'float','longitude'=>'float','recorded_at'=>'datetime'];
    public function order(): BelongsTo { return $this->belongsTo(Order::class); }
}
