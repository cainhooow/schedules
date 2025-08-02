<?php

namespace App\Traits;

use Godruoyi\Snowflake\Snowflake;

trait HasSnowflakeId
{
    //
    public static function bootHasSnowflakeId()
    {
        static::creating(function ($model) {
            $snowflake = new Snowflake();
            $model->{$model->getKeyName()} = $snowflake->id();
        });
    }
}
