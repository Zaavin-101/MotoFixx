<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class MotorBrand extends Model { protected $fillable=['name']; public function models(){return $this->hasMany(MotorModel::class);} }
