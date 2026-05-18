<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUnitReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // auth middleware handles this
    }

    public function rules(): array
    {
        return [
            'unit_id'     => ['required', 'integer', 'exists:units,id'],
            'booking_id'  => ['required', 'integer', 'exists:bookings,id'],
            'type'        => ['required', 'in:cleanliness,maintenance,noise,other'],
            'description' => ['required', 'string', 'min:10', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'type.in'              => 'Type must be one of: cleanliness, maintenance, noise, other.',
            'description.min'      => 'Description must be at least 10 characters.',
            'booking_id.exists'    => 'Booking not found.',
            'unit_id.exists'       => 'Unit not found.',
        ];
    }
}
