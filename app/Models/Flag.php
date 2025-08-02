<?php

namespace App\Models;

use App\Traits\HasSnowflakeId;
use Illuminate\Database\Eloquent\Model;

class Flag extends Model
{
    use HasSnowflakeId;

    public $incrementing = false;
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
