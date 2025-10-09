<?php

namespace App\Services;

use App\Exceptions\InvalidAddressException;
use App\Models\User;
use App\Repositories\ServiceRepository;

class ServiceServices
{
    public function __construct(
        protected $repository = new ServiceRepository(),
        protected $addressService = new AddressServices()
    ) {
    }

    public function index()
    {
        return $this->repository->index();
    }

    public function getById(int $serviceId)
    {
        return $this->repository->getById($serviceId);
    }

    public function store(User $user, array $data)
    {
        if (empty($data['address_id']) && !$defaultAddress = $this->addressService->hasDefault($user)) {
            throw new InvalidAddressException('O usuário não possui nenhum endereço cadastrado como padrão');
        }

        if (empty($data['address_id'])) {
            $data['address_id'] = $defaultAddress->id;
        }

        return $this->repository->store($data);
    }

    public function update(int $serviceId, $data)
    {
        return $this->repository->update($serviceId, $data);
    }


    public function delete(int $serviceId)
    {
        return $this->repository->delete($serviceId);
    }
}
