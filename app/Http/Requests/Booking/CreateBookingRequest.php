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
            'quantity' => ['required', 'integer', 'min:1'],
            'total_price' => ['required', 'integer'],
            'passenger_name' => ['required', 'string'],
            'passenger_phone' => ['required', 'string'],
        ];
    }

    public function failedValidation(Validator $validator)
    {
        if ($validator->fails()) {
            return $this->validationResponse($validator->errors());
        }
    }

    /**
     * Get the validated data from the request.
     * This adds the user_id automatically from the authenticated user.
     *
     * @param string|null $key
     * @param mixed $default
     * @return array|mixed
     */
    public function validated($key = null, $default = null)
    {
        $data = parent::validated();
        $data['user_id'] = auth()->id();

        // Jika $key diberikan, return nilai spesifik
        if ($key) {
            return data_get($data, $key, $default);
        }

        return $data;
    }
}