<?php

namespace App\Http\Requests\Booking;

use App\Models\Booking;
use App\Traits\ApiResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;

class CreateBookingRequest extends FormRequest
{
    use ApiResponse;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'schedule_id' => ['required', 'exists:schedules,id'],
            'user_id' => ['required', 'exists:users,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'total_price' => ['required', 'integer'],
        ];
    }

    public function failedValidation(Validator $validator)
    {
        if ($validator->fails()) {
            return $this->validationResponse($validator->errors());
        }
    }
}