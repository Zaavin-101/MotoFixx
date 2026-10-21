<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Sparepart extends Model {
    protected $fillable = ['name','slug','sku','category','description','price','stock','image_url','is_active'];
    protected $casts = ['price'=>'integer','stock'=>'integer','is_active'=>'boolean'];
    public function orderItems(): HasMany { return $this->hasMany(OrderItem::class); }
}
