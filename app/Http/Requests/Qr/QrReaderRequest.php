<?php

namespace App\Http\Requests\Qr;

use App\Traits\ApiResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class QrReaderRequest extends FormRequest
{
    use ApiResponse;

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
            'ticket_number' => ['required', 'string', 'exists:tickets,ticket_number']
        ];
    }

    public function failedValidation(Validator $validator)
    {
        if ($validator->fails()) {
            return $this->validationResponse($validator->errors());
        }
    }
}
