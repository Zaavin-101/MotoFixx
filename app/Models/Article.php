<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Article extends Model { protected $fillable=['title','slug','excerpt','content','image','published_at','is_published']; protected $casts=['published_at'=>'datetime','is_published'=>'boolean']; }
