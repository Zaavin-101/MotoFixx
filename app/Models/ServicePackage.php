<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
class ServicePackage extends Model {
    protected $table = 'packages';
    protected $fillable = ['name','slug','description','price','service_type','image_url','features','is_active'];
    protected $casts = ['price'=>'integer','features'=>'array','is_active'=>'boolean'];
    public function orderItems(): HasMany { return $this->hasMany(OrderItem::class, 'package_id'); }
}
