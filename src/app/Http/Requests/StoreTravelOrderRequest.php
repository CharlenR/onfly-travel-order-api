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
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'requester_name' => 'required|string|max:255|min:2',
            'destination'    => 'required|string|max:255|min:2',
            'departure_date' => 'required|date_format:Y-m-d|after_or_equal:today|before_or_equal:+5 years',
            'return_date'    => 'required|date_format:Y-m-d|after:departure_date|before_or_equal:+5 years',
        ];
    }

    public function messages(): array
    {
        return [
            'requester_name.required' => 'Requester name is required.',
            'requester_name.string' => 'Requester name must be a string.',
            'requester_name.max' => 'Requester name must not exceed 255 characters.',
            'requester_name.min' => 'Requester name must be at least 2 characters.',
            'destination.required' => 'Destination is required.',
            'destination.string' => 'Destination must be a string.',
            'destination.max' => 'Destination must not exceed 255 characters.',
            'destination.min' => 'Destination must be at least 2 characters.',
            'departure_date.required' => 'Departure date is required.',
            'departure_date.date_format' => 'Departure date must be in format Y-m-d.',
            'departure_date.after_or_equal' => 'Departure date must be today or in the future.',
            'departure_date.before_or_equal' => 'Departure date must be within 5 years.',
            'return_date.required' => 'Return date is required.',
            'return_date.date_format' => 'Return date must be in format Y-m-d.',
            'return_date.after' => 'Return date must be after departure date.',
            'return_date.before_or_equal' => 'Return date must be within 5 years.',
        ];
    }
}
