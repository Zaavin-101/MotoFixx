<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ServiceProgress extends Model { protected $fillable=['booking_id','status','title','description','progress_percent']; public function booking(){return $this->belongsTo(Booking::class);} }
