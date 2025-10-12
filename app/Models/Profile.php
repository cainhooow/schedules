<?php

namespace App\Models;

use App\Traits\HasSnowflakeId;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
     use HasSnowflakeId;

     public $incrementing = false;
     protected $fillable = [
          'name',
          'bio',
          'avatar',
          'phone',
          'user_id'
     ];

     public function user()
     {
          return $this->belongsTo(User::class);
     }
}
