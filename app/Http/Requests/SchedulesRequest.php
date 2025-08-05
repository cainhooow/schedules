<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class SchedulesRequest extends FormRequest
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
            'day_of_week' => [
                'required',
                'string',
                'regex:/^(monday|tuesday|wednesday|thursday|friday|saturday|sunday)$/'
            ],
            'start_time' => 'required|date_format:H:i:s|before:end_time',
            'end_time' => 'required|date_format:H:i:s|after:start_time'
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
            'day_of_week.required' => 'O campo day_of_week é obrigatorio',
            'day_of_week.regex' => 'O campo so aceita os valores: monday|tuesday|wednesday|thursday|friday|saturday|sunday',
            'start_time.required' => 'O campo start_time é obrigatorio',
            'end_time.required' => 'O campo end_time é obrigatorio',
            'start_time.date_format' => 'O campo start_time aceita apenas o formato H:i:s',
            'end_time.date_format' => 'O campo end_time aceita apenas o formato H:i:s',
            'start_time.before' => 'O start_time não pode ser maior que end_time',
            'end_time.after' => 'O end_time não pode ser menor que start_time'
        ];
    }
}
