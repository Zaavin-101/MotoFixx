<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class CustomerMotor extends Model { protected $fillable=['user_id','motor_brand_id','motor_model_id','plate_number','year','color']; public function user(){return $this->belongsTo(User::class);} public function brand(){return $this->belongsTo(MotorBrand::class,'motor_brand_id');} public function model(){return $this->belongsTo(MotorModel::class,'motor_model_id');} public function bookings(){return $this->hasMany(Booking::class);} }
