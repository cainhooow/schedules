<?php

namespace App\Services;

use App\Exceptions\InvalidScheduleException;
use App\Notifications\NewCommitment;
use App\Repositories\CommitmentRepository;
use Illuminate\Support\Facades\DB;
use Log;

class CommitmentServices
{
     public function __construct(
          protected CommitmentRepository $repository = new CommitmentRepository(),
          protected ScheduleServices $scheduleServices = new ScheduleServices(),
          protected ServiceServices $serviceServices = new ServiceServices(),
          protected UserServices $userServices = new UserServices(),
     ) {
     }

     public function getAll()
     {
          return $this->repository->index();
     }

     public function getById(int $id)
     {
          return $this->repository->getById($id);
     }

     public function getAllByCustomerId(int $customerId)
     {
          return $this->repository->getAllByCustomerId($customerId);
     }

     public function getAllByServiceId(int $serviceId)
     {
          return $this->repository->getAllByServiceId($serviceId);
     }

     public function store(array $data)
     {
          $service = $this->serviceServices->getById($data["service_id"]);
          $customer = $this->userServices->getById($data["customer_id"]);
          $schedule = $this->scheduleServices->getById($data["schedule_id"]);

          if (!$service || !$customer || !$schedule) {
               throw new InvalidScheduleException("Esse serviço, horario, ou cliente não existem");
          }

          if (!$schedule->available) {
               throw new InvalidScheduleException("Este horario não esta disponivel para agendamento");
          }

          DB::beginTransaction();
          try {
               $this->scheduleServices->setAvailabe($schedule->id, false);
               $commitment = $this->repository->store($data);

               $customer->notifyNow(new NewCommitment(
                    'client',
                    $customer,
                    $service->user,
                    $commitment
               ));

               Log::debug(json_encode($customer));

               $service->user->notifyNow(new NewCommitment(
                    'provider',
                    $customer,
                    $service->user,
                    $commitment
               ));

               DB::commit();
               return $commitment;
          } catch (\Exception $e) {
               DB::rollBack();
               Log::error('' . $e->getMessage());
               throw new InvalidScheduleException($e->getMessage());
          }
     }
}
