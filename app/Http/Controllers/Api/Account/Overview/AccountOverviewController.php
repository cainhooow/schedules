<?php
namespace App\Http\Controllers\Api\Account\Overview;

use App\Services\AccountStatsServices;
use Illuminate\Support\Facades\Auth;

class AccountOverviewController
{
     public function __construct(protected $accountStatsService = new AccountStatsServices())
     {
     }

     /**
      * @OA\Get(
      *   path="/api/v1/me/account/overview/pending-tasks",
      *   tags={"Conta"},
      *   summary="Processo de cadastro incompletas",
      *   @OA\Response(
      *     response=200,
      *     description="OK"
      *   )
      * )
      * @return \Illuminate\Http\JsonResponse
      */
     public function pendingAccountTasks()
     {
          $user = Auth::user();
          return response()->json([
               'pendings' => $this->accountStatsService->getPendingAccountTasks($user->id)
          ]);
     }

     /**
      * @OA\Get(
      *   path="/api/v1/me/account/overview/events-calendar",
      *   tags={"Conta", "Calendario"},
      *   summary="Retorna uma estrutura para montar calendarios de eventos",
      *   @OA\Response(
      *     response=200,
      *     description="OK"
      *   )
      * )
      * @return \Illuminate\Http\JsonResponse
      */
     public function eventsCalendar()
     {
          $user = Auth::user();
          return response()->json(
               $this->accountStatsService->getSchedulesCalendar($user->id)
          );
     }

     /**
      * @OA\Get(
      *   path="/api/v1/me/account/overview/demands",
      *   tags={"Contas", "Demandas"},
      *   summary="Mostrar estatisticas gerais sobre as demandas de agendamentos",
      *   @OA\Response(
      *     response=200,
      *     description="OK",
      *   )
      * )
      * @return \Illuminate\Http\JsonResponse
      */
     public function servicesDemands()
     {
          $user = Auth::user();
          return response()->json($this->accountStatsService->getServicesDemands($user->id));
     }
}
