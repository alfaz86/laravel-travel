<?php

namespace App\Http\Requests\Schedule;

use Illuminate\Foundation\Http\FormRequest;

class SearchScheduleRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'origin' => 'required|integer',
            'destination' => 'required|integer',
            'passengers' => 'required|integer',
            'date' => 'required|date',
        ];
    }
}
