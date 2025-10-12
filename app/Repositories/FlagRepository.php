<?php

namespace App\Repositories;

use App\Constants\Flags;
use App\Interfaces\FlagRepositoryInterface;
use App\Models\Flag;

class FlagRepository implements FlagRepositoryInterface
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
