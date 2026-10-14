<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class MotorModel extends Model { protected $fillable=['motor_brand_id','name']; public function brand(){return $this->belongsTo(MotorBrand::class,'motor_brand_id');} public function motors(){return $this->hasMany(CustomerMotor::class);} }
