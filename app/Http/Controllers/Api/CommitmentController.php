<?php

namespace App\Http\Controllers\Api;

use App\Constants\Flags;
use App\Http\Controllers\Controller;
use App\Http\Resources\CommitmentResource;
use App\Services\CommitmentServices;
use App\Services\FlagServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommitmentController extends Controller
{
     public function __construct(protected $service = new CommitmentServices(), protected $flagService = new FlagServices())
     {
     }

     /**
      * @OA\Get(
      *   path="/api/v1/me/commitments",
      *   tags={"Agenda"},
      *   summary="Compromissos agendados cliente/provedor",
      *   @OA\Response(
      *     response=200,
      *     description="OK",
      *   )
      * )
      * @param \Illuminate\Http\Request $request
      * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
      */
     public function index(Request $request)
     {
          $commitments = $this->flagService->userHas(Auth::user(), Flags::ServiceProvider)
               ? Auth::user()->services->flatMap->commitments
               : Auth::user()->commitments;

          return CommitmentResource::collection($commitments);
     }

     /**
      * @OA\Get(
      *   path="/api/v1/me/commitments/{commitmentId}",
      *   tags={"Agenda"},
      *   summary="Informações sobre um compromisso",
      *   @OA\Response(
      *     response=200,
      *     description="OK",
      *   )
      * )
      * @param int $commitmentId
      * @return CommitmentResource
      */
     public function show(int $commitmentId)
     {
          $commitment = $this->service->getById($commitmentId);
          return new CommitmentResource($commitment);
     }
}
