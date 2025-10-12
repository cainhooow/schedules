<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Schema(
 *     schema="RegisterRequest",
 *     title="Request-Body Registro",
 *     type="object",
 *     required={"username", "email", "password"},
 *     @OA\Property(
 *         property="username",
 *         type="string",
 *         pattern="^[a-z][a-z0-9]*(?:[._][a-z0-9]+)*$",
 *         example="usuario123",
 *         description="Nome de usuário único, começando com letra minúscula, pode conter números, '.' e '_'"
 *     ),
 *     @OA\Property(
 *         property="email",
 *         type="string",
 *         format="email",
 *         example="usuario@example.com",
 *         description="Endereço de email único e válido"
 *     ),
 *     @OA\Property(
 *         property="password",
 *         type="string",
 *         minLength=6,
 *         format="password",
 *         example="senhaSegura123",
 *         description="Senha com no mínimo 6 caracteres"
 *     )
 * )
 */
class RegisterRequest extends FormRequest
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
               'username' => 'required|string|unique:users,username|regex:/^[a-z][a-z0-9]*(?:[._][a-z0-9]+)*$/',
               'email' => 'required|email|unique:users,email',
               'password' => 'required|string|min:6'
          ];
     }

     public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
     {
          throw new HttpResponseException(response()->json([
               'success' => false,
               'message' => 'Validation errors',
               'data' => $validator->errors()
          ], Response::HTTP_BAD_REQUEST));
     }

     public function messages()
     {
          return [
               'username.required' => 'O campo nome é obrigatorio',
               'username.unique' => 'Este nome de usuário já esta em uso',
               'email.required' => 'O campo email é obrigatorio',
               'email.unique' => 'Já existe uma conta cadastrada com este endereço de email',
               'password.required' => 'O campo senha é obrigatorio',
          ];
     }
}
