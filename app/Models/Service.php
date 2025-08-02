<?php

namespace App\Models;

use App\Traits\HasSnowflakeId;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    //
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

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
