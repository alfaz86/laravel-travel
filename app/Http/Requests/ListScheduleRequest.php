<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ListScheduleRequest extends FormRequest
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
