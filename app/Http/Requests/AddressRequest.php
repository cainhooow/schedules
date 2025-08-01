<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

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
