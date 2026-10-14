<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Booking extends Model { protected $fillable=['user_id','customer_motor_id','mechanic_id','service_package_id','booking_date','booking_time','complaint','estimated_price','status']; protected $casts=['booking_date'=>'date','estimated_price'=>'decimal:2']; public function user(){return $this->belongsTo(User::class);} public function customerMotor(){return $this->belongsTo(CustomerMotor::class);} public function mechanic(){return $this->belongsTo(Mechanic::class);} public function servicePackage(){return $this->belongsTo(ServicePackage::class);} public function progress(){return $this->hasMany(ServiceProgress::class);} }
