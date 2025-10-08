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
    public function __construct(protected $service = new FlagServices())
    {
    }

    /**
     * @OA\Post(
     *   path="/api/v1/me/account/type",
     *   tags={"Conta"},
     *   summary="Definir o tipo da conta de um usuário",
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent( 
     *       type="string",
     *       required={"type"},
     *       @OA\Property(property="type", type="string")
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="O tipo da conta foi definido com sucesso",
     *     @OA\JsonContent(
     *       type="string",
     *       @OA\Property(property="type", type="string")
     *     )
     *   ),
     * )
     * @param \App\Http\Requests\AccountRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function defineType(AccountRequest $request)
    {
        $data = $request->validated();
        $type = (string) strtoupper($data['type']);

        $user = Auth::user();
        if ($this->service->userHas($user, Flags::Account_Completed_Tasks)) {
            return response()->json(['message' => 'Você já definiu todos os passos.'], Response::HTTP_UNAUTHORIZED);
        }

        try {
            $flagsType = match ($type) {
                'CUSTOMER' => [
                    Flags::Customer,
                    Flags::Can_Contract_Services,
                    Flags::Account_Task_Level_2
                ],
                'SERVICE' => [
                    Flags::ServiceProvider,
                    Flags::Can_Create_Services,
                    Flags::Can_Update_Services,
                    Flags::Account_Task_Level_2
                ],
                'ENTERPRISE' => [
                    Flags::Enterprise,
                    Flags::Can_Create_Services,
                    Flags::Can_Update_Services,
                    Flags::Account_Task_Level_2
                ],
            };

            $this->service->assignToUser($user, $flagsType);
            $this->service->removeFromUser($user, [Flags::Account_Task_Level_1]);

            return response()->json(['type' => $type], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['error' => true, 'message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
