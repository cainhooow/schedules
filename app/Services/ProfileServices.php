<?php

namespace App\Services;

use App\Constants\Flags;
use App\Repositories\ProfileRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class ProfileServices
{
    public function __construct(protected $repository = new ProfileRepository(), protected $service = new FlagServices())
    {
    }

    public function getById(int $id)
    {
        return $this->repository->getByid($id);
    }

    public function store(array $data)
    {
        DB::beginTransaction();
        try {
            $data = $this->repository->store($data);
            $this->service->assignToUser($data->user, [Flags::AccountTaskLevel3]);
            $this->service->removeFromUser($data->user, [Flags::AccountTaskLevel2]);

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
