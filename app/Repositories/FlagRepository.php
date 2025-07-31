<?php

namespace App\Repositories;

use App\Constants\FlagConstant;
use App\Interfaces\FlagInterface;
use App\Models\Flag;

class FlagRepository implements FlagInterface
{
    private $model;

    public function __construct()
    {
        $this->model = new Flag();
    }

    public function selectFlags(array $flags)
    {
        return $this->model->whereIn('name', $flags);
    }

    public function selectIdsByName(array $flags)
    {
        return $this->model->whereIn('name', $flags)->pluck('id');
    }
}
