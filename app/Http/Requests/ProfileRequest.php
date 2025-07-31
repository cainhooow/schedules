<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

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
