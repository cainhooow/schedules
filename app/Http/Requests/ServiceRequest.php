<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Schema(
 *     schema="ServiceRequest",
 *     title="Request-Body Serviço",
 *     type="object",
 *     required={"name"},
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         maxLength=100,
 *         example="Corte de Cabelo Masculino",
 *         description="Nome do serviço (obrigatório)"
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="string",
 *         maxLength=2000,
 *         example="Serviço de corte de cabelo com máquina e tesoura.",
 *         description="Descrição detalhada do serviço (opcional)"
 *     ),
 *     @OA\Property(
 *         property="price",
 *         type="number",
 *         format="float",
 *         example=49.90,
 *         description="Preço base do serviço (opcional)"
 *     ),
 *     @OA\Property(
 *         property="min_price",
 *         type="number",
 *         format="float",
 *         example=40.00,
 *         description="Preço mínimo (opcional)"
 *     ),
 *     @OA\Property(
 *         property="max_price",
 *         type="number",
 *         format="float",
 *         example=60.00,
 *         description="Preço máximo (opcional)"
 *     )
 * )
 */
class ServiceRequest extends FormRequest
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
               'name' => 'required|string|max:100',
               'description' => 'string|max:2000',
               'price' => 'decimal:2,2',
               'min_price' => 'decimal:2,2',
               'max_price' => 'decimal:2,2'
          ];
     }

     public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
     {
          throw new HttpResponseException(response()->json(
               [
                    'success' => false,
                    'message' => 'Validation errors',
                    'data' => $validator->errors()
               ],
               Response::HTTP_BAD_REQUEST
          ));
     }

     public function messages()
     {
          return [
               'name.required' => 'O campo name é obrigatorio',
               'description.max' => 'O campo description tem o limite maximo de 2000 caracteres',
               'price.decimal' => 'O campo price deve ser um decimal',
               'min_price.decimal' => 'o campo min_price deve ser um decimal',
               'max_price.decimal' => 'o campo max_price deve ser um decimal',
          ];
     }
}
