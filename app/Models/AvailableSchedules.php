<?php

namespace App\Models;

use App\Policies\SchedulePolicy;
use App\Traits\HasSnowflakeId;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Model;

#[UsePolicy(SchedulePolicy::class)]
class AvailableSchedules extends Model
{
     use HasSnowflakeId;

     protected $fillable = [
          'day_of_week',
          'start_time',
          'end_time',
          'available',
          'service_id'
     ];

     public function service()
     {
          return $this->belongsTo(Service::class);
     }
}
