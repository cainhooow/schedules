<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

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
