<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Sparepart extends Model { protected $fillable=['sparepart_category_id','name','sku','description','price','stock','image','is_active']; protected $casts=['price'=>'decimal:2','is_active'=>'boolean']; public function category(){return $this->belongsTo(SparepartCategory::class,'sparepart_category_id');} }
