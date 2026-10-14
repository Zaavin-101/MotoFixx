<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Service extends Model { protected $fillable=['service_category_id','name','description','price','duration_minutes','is_active']; protected $casts=['price'=>'decimal:2','is_active'=>'boolean']; public function category(){return $this->belongsTo(ServiceCategory::class,'service_category_id');} }
