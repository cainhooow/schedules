<?php

namespace App\Models;

use App\Policies\UserServicesPolicy;
use App\Traits\HasSnowflakeId;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasSnowflakeId;

    public $incrementing = false;
    protected $fillable = [
        'name',
        'description',
        'price',
        'min_price',
        'max_price',
        'address_id',
        'user_id'
    ];

    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'address_id' => 'integer',
        'price' => 'float',
        'max_price' => 'float',
        'min_price' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function schedules()
    {
        return $this->hasMany(AvailableSchedules::class);
    }
}
