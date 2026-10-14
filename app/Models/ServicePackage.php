<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ServicePackage extends Model { protected $fillable=['service_id','name','description','price','duration_minutes','is_active']; protected $casts=['price'=>'decimal:2','is_active'=>'boolean']; public function service(){return $this->belongsTo(Service::class);} public function bookings(){return $this->hasMany(Booking::class);} }
