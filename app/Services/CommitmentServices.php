<?php

namespace App\Services;

use App\Exceptions\InvalidScheduleException;
use App\Models\AvailableSchedules;
use App\Notifications\NewCommitmentNotification;
use App\Repositories\CommitmentRepository;
use Carbon\Carbon;
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

          if (empty($data['schedule_for'])) {
               throw new InvalidScheduleException('A data do agendamento (schedule_for) é obrigatória');
          }

          $scheduleFor = Carbon::parse($data['schedule_for']);

          if ($scheduleFor->isBefore(Carbon::today())) {
               throw new InvalidScheduleException('Não é possivel agendar para uma data anterior a de hoje.');
          }

          $daysMap = [
               0 => 'sunday',
               1 => 'monday',
               2 => 'tuesday',
               3 => 'wednesday',
               4 => 'thursday',
               5 => 'friday',
               6 => 'saturday'
          ];

          $dayOfWeekString = $daysMap[$scheduleFor->dayOfWeek];

          if ($this->repository->getByScheduleIdWhereDate($schedule->id, $scheduleFor)->exists()) {
               throw new InvalidScheduleException('Já existe um compromisso marcado para este horário');
          }

          if ($dayOfWeekString !== $schedule->day_of_week) {
               throw new InvalidScheduleException(
                    "A data escolhida ({$dayOfWeekString}) não corresponde ao dia configurado no horario ({$schedule->day_of_week})."
               );
          }

          $time = $scheduleFor->format('H:i:s');

          if ($time < $schedule->start_time || $time > $schedule->end_time) {
               throw new InvalidScheduleException("O horário informado ({$time}) está fora do intervalo permitido ({$schedule->start_time}–{$schedule->end_time}).");
          }

          DB::beginTransaction();
          try {
               $this->scheduleServices->setAvailabe($schedule->id, false);
               $commitment = $this->repository->store([
                    ...$data,
                    'schedule_for' => $scheduleFor
               ]);

               $customer->notifyNow(new NewCommitmentNotification(
                    'client',
                    $customer,
                    $service->user,
                    $commitment
               ));

               Log::debug(json_encode($customer));

               $service->user->notifyNow(new NewCommitmentNotification(
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
