<?php

namespace App\Http\Controllers\Api\Account;

use App\Constants\FlagConstant;
use App\Http\Controllers\Controller;
use App\Http\Requests\AccountRequest;
use App\Services\FlagService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AccountController extends Controller
{
    public function __construct(protected $service = new FlagService()) {}

    //
    public function defineType(AccountRequest $request)
    {
        $data = $request->validated();
        $type = (string) strtoupper($data['type']);

        $user = Auth::user();
        if ($this->service->userHas($user, FlagConstant::ACCOUNT_COMPLETED_TASKS)) {
            return response()->json(['message' => 'Você já definiu todos os passos.'], Response::HTTP_FORBIDDEN);
        }

        try {
            $flagsType = match ($type) {
                'CUSTOMER' => [FlagConstant::CUSTOMER, FlagConstant::ACCOUNT_TASK_LEVEL_2],
                'SERVICE' => [FlagConstant::SERVICE_PROVIDER, FlagConstant::ACCOUNT_TASK_LEVEL_2],
                'ENTERPRISE' => [FlagConstant::ENTERPRISE, FlagConstant::ACCOUNT_TASK_LEVEL_2],
            };

            $this->service->assignToUser($user, $flagsType);
            $this->service->removeFromUser($user, [FlagConstant::ACCOUNT_TASK_LEVEL_1]);

            return response()->json(['type' => $type], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['error' => true, 'message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
