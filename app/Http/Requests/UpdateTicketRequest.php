<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateTicketRequest extends FormRequest
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
            'plate_number' => 'required|string|max:20|regex:/^[A-Za-z0-9-]+$/', // Alphanumeric + hyphen
            'car_type' => 'required|string|max:50',
            'trip_details' => 'required|string|max:1000',
            'passenger_count' => 'required|integer|min:1',
            'departure_time' => 'required|date|after_or_equal:now', // Must be in the future
            'expected_return_time' => 'nullable|date|after:departure_time', // Must be after departure
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => 'Ticket Failed to Update',
            'errors' => $validator->errors()
        ], 422));
    }
}