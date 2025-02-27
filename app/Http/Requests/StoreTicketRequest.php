<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTicketRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id', // Must exist in the users table
            'plate_number' => 'required|string|max:20|regex:/^[A-Za-z0-9-]+$/', // Alphanumeric + hyphen
            'car_type' => 'required|string|max:50',
            'trip_details' => 'required|string|max:1000',
            'passenger_count' => 'required|integer|min:1',
            'departure_time' => 'required|date|after_or_equal:now', // Must be in the future
            'expected_return_time' => 'nullable|date|after:departure_time', // Must be after departure
        ];
    }
}
