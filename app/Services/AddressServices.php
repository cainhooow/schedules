<?php

namespace App\Services;

use App\Constants\FlagConstant;
use App\Exceptions\InvalidAddressException;
use App\Models\Address;
use App\Models\User;
use App\Repositories\AddressRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class AddressServices
{
    public function __construct(
        protected $repository = new AddressRepository(),
        protected $flagsService = new FlagServices(),
        protected $userService = new UserServices(),
    ) {}

    public function getById(int $id)
    {
        return $this->repository->getById($id);
    }

    public function create(array $data)
    {
        // para o processo de criação da conta, o primeiro endereço é padrão.
        $data['default'] = true;

        try {
            DB::beginTransaction();
            $data = $this->repository->store($data);

            $this->flagsService->removeFromUser($data->user, [FlagConstant::ACCOUNT_TASK_LEVEL_3]);
            $this->flagsService->assignToUser($data->user, [FlagConstant::ACCOUNT_COMPLETED_TASKS]);

            DB::commit();
            return $data;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function store(array $data): Address
    {
        unset($data['default']);
        return $this->repository->store($data);
    }

    public function update(Address $address, array $data): bool|int
    {
        $defaultAddress = $address->user->addresses()->where('default', true)->first();

        if (($defaultAddress && $address->id === $defaultAddress->id) ||
            empty($data['default']) || $data['default'] === false
        ) {
            return $this->repository->update($address->id, $data);
        }

        if ($defaultAddress && $defaultAddress->id !== $address->id) {
            $defaultAddress->update(['default' => false]);
        }

        return $this->repository->update(
            $address->id,
            array_merge($data, ['default' => true])
        );
    }

    public function delete(User $user, int $addressId): bool|null
    {
        if ($user->addresses()->count() > 1) {
            return $this->repository->delete($addressId);
        }

        try {
            DB::beginTransaction();

            $this->flagsService->assignToUser($user, [FlagConstant::ACCOUNT_TASK_LEVEL_3]);
            $this->flagsService->removeFromUser($user, [FlagConstant::ACCOUNT_COMPLETED_TASKS]);

            DB::commit();

            return $this->repository->delete($addressId);
        } catch (InvalidAddressException $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function hasDefault(User $user): Address|null
    {
        return $this->repository->hasDefault($user->id);
    }
}
