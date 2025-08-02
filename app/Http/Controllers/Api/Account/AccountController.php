<?php

namespace App\Http\Controllers\Api\Account;

use App\Constants\Flags;
use App\Http\Controllers\Controller;
use App\Http\Requests\AccountRequest;
use App\Services\FlagServices;
use Exception;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AccountController extends Controller
{
    public function __construct(protected $service = new FlagServices()) {}

    //
    public function defineType(AccountRequest $request)
    {
        $data = $request->validated();
        $type = (string) strtoupper($data['type']);

        $user = Auth::user();
        if ($this->service->userHas($user, Flags::ACCOUNT_COMPLETED_TASKS)) {
            return response()->json(['message' => 'Você já definiu todos os passos.'], Response::HTTP_UNAUTHORIZED);
        }

        try {
            $flagsType = match ($type) {
                'CUSTOMER' => [
                    Flags::CUSTOMER,
                    Flags::CAN_CONTRACT_SERVICES,
                    Flags::ACCOUNT_TASK_LEVEL_2
                ],
                'SERVICE' => [
                    Flags::SERVICE_PROVIDER,
                    Flags::CAN_CREATE_SERVICES,
                    Flags::CAN_UPDATE_SERVICES,
                    Flags::ACCOUNT_TASK_LEVEL_2
                ],
                'ENTERPRISE' => [
                    Flags::ENTERPRISE,
                    Flags::CAN_CREATE_SERVICES,
                    Flags::CAN_UPDATE_SERVICES,
                    Flags::ACCOUNT_TASK_LEVEL_2
                ],
            };

            $this->service->assignToUser($user, $flagsType);
            $this->service->removeFromUser($user, [Flags::ACCOUNT_TASK_LEVEL_1]);

            return response()->json(['type' => $type], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['error' => true, 'message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
