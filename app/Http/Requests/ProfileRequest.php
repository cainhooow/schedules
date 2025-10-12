<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

/**
 * @OA\Schema(
 *     schema="ProfileRequest",
 *     title="Request-Body Perfil",
 *     type="object",
 *     required={"name"},
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         minLength=2,
 *         example="João Silva",
 *         description="Nome completo do usuário (obrigatório)"
 *     ),
 *     @OA\Property(
 *         property="bio",
 *         type="string",
 *         maxLength=1500,
 *         example="Sou um profissional dedicado com anos de experiência...",
 *         description="Biografia ou descrição do usuário (opcional)"
 *     ),
 *     @OA\Property(
 *         property="avatar",
 *         type="string",
 *         format="url",
 *         example="https://example.com/avatar.jpg",
 *         description="URL do avatar do usuário (opcional)"
 *     ),
 *     @OA\Property(
 *         property="phone",
 *         type="string",
 *         example="+55 11 91234-5678",
 *         description="Telefone de contato (opcional)"
 *     )
 * )
 */
class ProfileRequest extends FormRequest
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
               'name' => 'required|string|min:2',
               'bio' => 'string|max:1500',
               'avatar' => 'url',
               'phone' => 'string',
          ];
     }

     public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
     {
          throw new HttpResponseException(response()->json([
               'success' => false,
               'message' => 'Validation errors',
               'data' => $validator->errors()
          ]));
     }
     public function messages()
     {
          return [
               'name.required' => 'O campo nome é obrigatorio',
               'name.min' => 'O campo nome precisa ter ao minimo 2 caracteres',
               'bio.max' => 'O campo bio aceita ao maximo 1500 caracteres',
               'avatar.url' => 'O campo avatar deve ser uma url valida',
          ];
     }
}
