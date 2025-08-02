<?php

namespace App\Models;

use App\Traits\HasSnowflakeId;
use Illuminate\Database\Eloquent\Model;

class Commitment extends Model
{
    //
    use HasSnowflakeId;

    public $incrementing = false;
}
