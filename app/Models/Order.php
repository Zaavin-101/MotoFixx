<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
class Order extends Model {
    protected $fillable = ['user_id','vehicle_id','mechanic_id','order_number','status','service_type','address','latitude','longitude','subtotal','visit_fee','total','payment_method','notes','mechanic_notes'];
    protected $casts = ['subtotal'=>'integer','visit_fee'=>'integer','total'=>'integer','latitude'=>'float','longitude'=>'float'];
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function vehicle(): BelongsTo { return $this->belongsTo(Vehicle::class); }
    public function mechanic(): BelongsTo { return $this->belongsTo(User::class, 'mechanic_id'); }
    public function items(): HasMany { return $this->hasMany(OrderItem::class); }
    public function payment(): HasOne { return $this->hasOne(Payment::class); }
    public function mechanicLocations(): HasMany { return $this->hasMany(MechanicLocation::class); }
}
