<?php

namespace App\Models;

use App\Traits\HasSnowflakeId;
use Illuminate\Database\Eloquent\Model;

class Commitment extends Model
{
    //
    use HasSnowflakeId;

    public $incrementing = false;

    protected $fillable = [
        'id',
        'comment',
        'status',
        'service_provider_id',
        'customer_id'
    ];

    public function serviceProvider() {
        return $this->belongsTo(User::class, 'service_provider_id');
    }

    public function customer() {
        return $this->belongsTo(User::class, 'customer_id');
    }
}
