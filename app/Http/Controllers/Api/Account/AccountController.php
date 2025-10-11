<?php

namespace App\Http\Controllers\Api\Account;

use App\Constants\Flags;
use App\Http\Controllers\Controller;
use App\Http\Requests\AccountRequest;
use App\Http\Requests\ForgotPasswdRequest;
use App\Services\FlagServices;
use App\Services\UserServices;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class AccountController extends Controller
{
    public function __construct(protected $userService = new UserServices(), protected $flagsService = new FlagServices())
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
        if ($this->flagsService->userHas($user, Flags::AccountCompletedTasks)) {
            return response()->json(['message' => 'Você já definiu todos os passos.'], Response::HTTP_UNAUTHORIZED);
        }

        try {
            $flagsType = match ($type) {
                'CUSTOMER' => [
                    Flags::Customer,
                    Flags::CanContractServices,
                    Flags::AccountTaskLevel2
                ],
                'SERVICE' => [
                    Flags::ServiceProvider,
                    Flags::CanCreateServices,
                    Flags::CanUpdateServices,
                    Flags::AccountTaskLevel2
                ],
                'ENTERPRISE' => [
                    Flags::Enterprise,
                    Flags::CanCreateServices,
                    Flags::CanUpdateServices,
                    Flags::AccountTaskLevel2
                ],
            };

            $this->flagsService->assignToUser($user, $flagsType);
            $this->flagsService->removeFromUser($user, [Flags::AccountTaskLevel1]);

            return response()->json(['type' => $type], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['error' => true, 'message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function forgotPassword(ForgotPasswdRequest $request)
    {
        $data = $request->validated();
        $status = Password::sendResetLink($data);

        if ($status == Password::RESET_LINK_SENT) {
            return [
                'success' => true,
                'message' => 'Enviamos um e-mail para o informado, verifique sua caixa de entrada'
            ];
        }

        throw ValidationException::withMessages([
            'email' => [trans($status)]
        ]);
    }

    public function resetPassword()
    {
        return response()->json([
            'success' => true,
            'message' => 'x'
        ]);
    }
}
