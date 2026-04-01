<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreTravelOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'requester_name' => 'required|string|max:255',
            'destination'    => 'required|string|max:255',
            'departure_date' => 'required|date|after_or_equal:today',
            'return_date'    => 'required|date|after:departure_date',
        ];
    }
}
