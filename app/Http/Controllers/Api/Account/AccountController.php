<?php

namespace App\Http\Controllers\Api\Account;

use App\Constants\Flags;
use App\Http\Controllers\Controller;
use App\Http\Requests\AccountRequest;
use App\Http\Requests\ForgotPasswdRequest;
use App\Http\Requests\ResetPasswdRequest;
use App\Mail\AccountPasswordChangedMail;
use App\Models\User;
use App\Services\AccountStatsServices;
use App\Services\FlagServices;
use App\Services\UserServices;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class AccountController extends Controller
{
     public function __construct(
          protected $userService = new UserServices(),
          protected $flagsService = new FlagServices(),
     ) {
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

          $assignProfileCreate = true;

          if ($this->flagsService->userHas($user, Flags::AccountTaskLevel3)) {
               $assignProfileCreate = false;
          }

          try {
               $flagsType = match ($type) {
                    'CUSTOMER' => [
                         Flags::Customer,
                         Flags::CanContractServices,
                         $assignProfileCreate ? Flags::AccountTaskLevel2 : ''
                    ],
                    'SERVICE' => [
                         Flags::ServiceProvider,
                         Flags::CanCreateServices,
                         Flags::CanUpdateServices,
                         $assignProfileCreate ? Flags::AccountTaskLevel2 : ''
                    ],
                    'ENTERPRISE' => [
                         Flags::Enterprise,
                         Flags::CanCreateServices,
                         Flags::CanUpdateServices,
                         $assignProfileCreate ? Flags::AccountTaskLevel2 : ''
                    ],
               };

               $this->flagsService->assignToUser($user, $flagsType);
               $this->flagsService->removeFromUser($user, [Flags::AccountTaskLevel1]);

               return response()->json(['type' => $type], Response::HTTP_OK);
          } catch (Exception $e) {
               return response()->json(['error' => true, 'message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
          }
     }

     /**
      * @OA\Post(
      *   path="/api/v1/auth/forgot-password",
      *   tags={"Conta"},
      *   summary="Solicitar um token de reset de senha",
      *   @OA\RequestBody(
      *     required=true,
      *     @OA\JsonContent(ref="#/components/schemas/ForgotPasswordRequest")
      *   ),
      *   @OA\Response(
      *     response=200,
      *     description="OK",
      *   )
      * )
      * @param \App\Http\Requests\ForgotPasswdRequest $request
      * @return array{message: string, success: bool}
      */
     public function forgotPassword(ForgotPasswdRequest $request)
     {
          $data = $request->validated();
          $status = Password::sendResetLink($data);

          if ($status == Password::RESET_LINK_SENT) {
               Log::info("Password reset link sent successfull");
               return [
                    'success' => true,
                    'message' => 'Enviamos um e-mail para o informado, verifique sua caixa de entrada'
               ];
          }

          Log::warning("Failed to send password reset token with status {$status}");
          throw ValidationException::withMessages([
               'email' => [trans($status)]
          ]);
     }

     /**
      * @OA\Post(
      *   path="/api/v1/auth/reset-password",
      *   tags={"Conta"},
      *   summary="Resetar senha com o token",
      *   @OA\RequestBody(
      *     required=true,
      *     @OA\JsonContent(ref="#/components/schemas/ResetPasswordRequest")
      *   ),
      *   @OA\Response(
      *     response=200,
      *     description="OK",
      *   )
      * )
      * @param \App\Http\Requests\ResetPasswdRequest $request
      * @return \Illuminate\Http\JsonResponse
      */
     public function resetPassword(ResetPasswdRequest $request)
     {
          $data = $request->validated();

          $status = Password::reset($data, function (User $user, $password) {
               $user->forceFill([
                    'password' => Hash::make($password)
               ])->setRememberToken(Str::random(60));
               $user->save();

               Mail::to($user->email)->send(new AccountPasswordChangedMail($user));
          });

          if ($status !== Password::PASSWORD_RESET) {
               Log::warning("User password reset failed. Reset status: {$status}");
               return response()->json([
                    'error' => true,
                    'message' => 'Ocorreu um erro ao tentar resetar a sua senha.',
                    'status' => __($status)
               ]);
          }

          Log::info("User password reset successfull");
          return response()->json([
               'success' => true,
               'message' => 'Senha alterada com sucesso!'
          ], Response::HTTP_OK);
     }
}
