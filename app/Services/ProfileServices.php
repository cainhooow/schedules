<?php

namespace App\Services;

use App\Constants\FlagConstant;
use App\Repositories\ProfileRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class ProfileServices
{
    public function __construct(protected $repository = new ProfileRepository(), protected $service = new FlagServices()) {}

    public function getById(int $id)
    {
        return $this->repository->getByid($id);
    }

    public function store(array $data)
    {
        try {
            DB::beginTransaction();

            $data = $this->repository->store($data);
            $this->service->assignToUser($data->user, [FlagConstant::ACCOUNT_TASK_LEVEL_3]);
            $this->service->removeFromUser($data->user, [FlagConstant::ACCOUNT_TASK_LEVEL_2]);

            DB::commit();
            return $data;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update(int $id, array $data)
    {
        return $this->repository->update($id, $data);
    }
}
