<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Vehicle extends Model {
    protected $fillable = ['user_id','name','brand','model','plate_number','year','odometer_km'];
    protected $casts = ['year'=>'integer','odometer_km'=>'integer'];
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function orders(): HasMany { return $this->hasMany(Order::class); }
}
