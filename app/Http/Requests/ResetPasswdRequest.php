<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Schema(
 *   schema="ResetPasswordRequest",
 *   title="Request-Body ResetPassword",
 *   type="object",
 *   required={"token", "email", "password", "password_confirmation"},
 *   @OA\Property(
 *     property="token",
 *     type="string",
 *     example="eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiYWRtaW4iOnRydWUsImlhdCI6MTUxNjIzOTAyMn0.KMUFsIDTnFmyG3nMiGM6H9FNFUROf3wh7SmqJp-QV30",
 *     description="Token de reset temporario para resetar a senha"
 *   ),
 *   @OA\Property(
 *     property="email",
 *     type="string",
 *     format="email",
 *     example="usuario@example.com",
 *     description="Endereço de e-mail válido cadastrado"
 *   ),
 *   @OA\Property(
 *     property="password",
 *     type="string",
 *     format="password",
 *     minLength=6,
 *     example="senhaSegura123",
 *     description="Senha com no mínimo 6 caracteres"
 *   ),
 *   @OA\Property(
 *     property="password_confirmation",
 *     type="string",
 *     format="password",
 *     minLength=6,
 *     example="senhaSegura123",
 *     description="Confirmação de password com no mínimo 6 caracteres"
 *   )
 * )
 */
class ResetPasswdRequest extends FormRequest
{
     /**
      * Determine if the user is authorized to make this request.
      */
     public function authorize(): bool
     {
          return true;
     }

     /**
      * Get the validation rules that apply to the request.
      *
      * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
      */
     public function rules(): array
     {
          return [
               'token' => 'required|string',
               'email' => 'required|email|exists:users,email',
               'password' => 'required|string|min:6',
               'password_confirmation' => 'required|string|min:6|same:password',
          ];
     }

     public function failedValidation(Validator $validator)
     {
          throw new HttpResponseException(response()->json([
               'success' => false,
               'message' => 'Validation errors',
               'data' => $validator->errors()
          ], Response::HTTP_BAD_REQUEST));
     }
}
