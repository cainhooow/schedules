<?php

namespace App\Models;

use App\Traits\HasSnowflakeId;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasSnowflakeId;

    public $incrementing = false;
    protected $fillable = [
        "default",
        "state",
        "city",
        "address",
        "street",
        "neighborhood",
        "number",
        "user_id"
    ];

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
