<?php

namespace App\Http\Requests;

use App\Models\Court;
use App\Services\BookingService;
use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'court_id' => ['required', 'integer', 'exists:courts,id'],
            'booking_date' => ['required', 'date', 'after_or_equal:today'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'notes' => ['nullable', 'string', 'max:500'],
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($validator->errors()->any()) {
                return;
            }

            $startHour = (int) substr($this->start_time, 0, 2);
            $endHour = (int) substr($this->end_time, 0, 2);

            // Validate operating hours
            if ($startHour < BookingService::OPEN_HOUR || $endHour > BookingService::CLOSE_HOUR) {
                $validator->errors()->add(
                    'start_time',
                    'Booking must be within operating hours (' . BookingService::OPEN_HOUR . ':00 - ' . BookingService::CLOSE_HOUR . ':00).'
                );
            }

            // Validate 1-hour slot
            if ($endHour - $startHour !== 1) {
                $validator->errors()->add(
                    'end_time',
                    'Booking must be exactly 1 hour.'
                );
            }

            // Validate court is active
            $court = Court::find($this->court_id);
            if ($court && !$court->is_active) {
                $validator->errors()->add(
                    'court_id',
                    'This court is currently unavailable.'
                );
            }
        });
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'court_id.required' => 'Please select a court.',
            'court_id.exists' => 'The selected court does not exist.',
            'booking_date.required' => 'Please select a date.',
            'booking_date.after_or_equal' => 'Booking date must be today or later.',
            'start_time.required' => 'Please select a time slot.',
            'end_time.required' => 'End time is required.',
            'end_time.after' => 'End time must be after start time.',
        ];
    }
}
