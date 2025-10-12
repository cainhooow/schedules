<?php

namespace App\Models;

use App\Traits\HasSnowflakeId;
use Illuminate\Database\Eloquent\Model;

class Commitment extends Model
{
     use HasSnowflakeId;

     public $incrementing = false;

     protected $fillable = [
          'comment',
          'status',
          'customer_id',
          'service_id',
          'schedule_id',
     ];

     public function service()
     {
          return $this->belongsTo(Service::class);
     }

     public function schedule()
     {
          return $this->belongsTo(AvailableSchedules::class);
     }

     public function customer()
     {
          return $this->belongsTo(User::class, 'customer_id');
     }
}
