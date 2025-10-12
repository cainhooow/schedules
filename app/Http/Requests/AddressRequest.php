<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

/**
 * @OA\Schema(
 *     schema="AddressRequest",
 *     title="Request-Body Endereço",
 *     type="object",
 *     required={"state", "city"},
 *     @OA\Property(
 *         property="default",
 *         type="boolean",
 *         example=true,
 *         description="Se este é o endereço padrão do usuário"
 *     ),
 *     @OA\Property(
 *         property="state",
 *         type="string",
 *         minLength=2,
 *         maxLength=3,
 *         example="SP",
 *         description="Estado (UF) obrigatório"
 *     ),
 *     @OA\Property(
 *         property="city",
 *         type="string",
 *         minLength=3,
 *         maxLength=30,
 *         example="São Paulo",
 *         description="Nome da cidade (obrigatório)"
 *     ),
 *     @OA\Property(
 *         property="address",
 *         type="string",
 *         maxLength=120,
 *         example="Avenida Paulista, 1578",
 *         description="Endereço completo"
 *     ),
 *     @OA\Property(
 *         property="street",
 *         type="string",
 *         maxLength=120,
 *         example="Rua das Flores",
 *         description="Nome da rua"
 *     ),
 *     @OA\Property(
 *         property="neighborhood",
 *         type="string",
 *         maxLength=120,
 *         example="Bela Vista",
 *         description="Nome do bairro"
 *     ),
 *     @OA\Property(
 *         property="number",
 *         type="string",
 *         example="42A",
 *         description="Número do imóvel (string por flexibilidade)"
 *     )
 * )
 */
class AddressRequest extends FormRequest
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
               'default' => 'boolean',
               'state' => 'required|string|max:3|min:2',
               'city' => 'required|string|min:3|max:30',
               'address' => 'string|max:120',
               'street' => 'string|max:120',
               'neighborhood' => 'string|max:120',
               'number' => 'string',
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
               'default.boolean' => 'O campo default deve ser um boleano',
               'state.required' => 'O campo estado é obrigatorio',
               'city.required' => 'O campo cidade é obrigatorio',
               'address.max' => 'O endereço tem o limite maximo de 120 caracteres',
               'street.max' => 'O nome da rua tem o limite maximo de 120 caracteres',
               'neighborhood.max' => 'O nome do bairro tem o limite maximo de 120 caracteres',
          ];
     }
}
