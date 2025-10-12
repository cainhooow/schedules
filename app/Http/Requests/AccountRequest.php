<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Schema(
 *     schema="AccountRequest",
 *     title="Request-Body Tipo de conta",
 *     type="object",
 *     required={"type"},
 *     @OA\Property(
 *         property="type",
 *         type="string",
 *         enum={"customer", "service", "enterprise"},
 *         example="customer",
 *         description="Tipo de conta: customer, service ou enterprise"
 *     )
 * )
 */
class AccountRequest extends FormRequest
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
               'type' => ['string', 'required', 'regex:/^(customer|service|enterprise)$/']
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
               'type.required' => 'O campo tipo é obrigatorio',
               'type.regex' => 'O tipo é invalido. Aceitos: customer, service, enterprise'
          ];
     }
}
