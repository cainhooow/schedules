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
    public function __construct(protected $service = new CommitmentServices(), protected $flagService = new FlagServices()) {}
    //
    public function index(Request $request)
    {
        $commitments = $this->flagService->userHas(Auth::user(), Flags::ServiceProvider)
            ? Auth::user()->services->flatMap->commitments
            : Auth::user()->commitments;

        return CommitmentResource::collection($commitments);
    }

    public function show(int $commitmentId)
    {
        $commitment = $this->service->getById($commitmentId);
        return new CommitmentResource($commitment);
    }
}
