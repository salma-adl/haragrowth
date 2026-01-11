<?php

namespace App\Filament\Resources\BookingResource\Pages;

use App\Filament\Resources\BookingResource;
use App\Models\Customer;
use App\Models\Booking;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Http;

class CreateBooking extends CreateRecord
{
    protected static string $resource = BookingResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Create or find customer
        $customer = Customer::firstOrCreate(
            ['email' => $data['customer_email']],
            [
                'name' => $data['customer_name'],
                'phone' => $data['customer_phone'],
                'age' => $data['customer_age'],
                'gender' => $data['customer_gender'],
            ]
        );

        // Generate booking code
        $prefix = 'HG';
        $datePart = date('Ymd');
        $randomPart = strtoupper(substr(md5(uniqid(rand(), true)), 0, 6));
        $bookingCode = $prefix . '-' . $datePart . '-' . $randomPart;

        // Set booking data
        $data['customer_id'] = $customer->id;
        $data['booking_code'] = $bookingCode;
        $data['status'] = 'booked';

        // Remove customer fields from booking data
        unset($data['customer_name']);
        unset($data['customer_email']);
        unset($data['customer_phone']);
        unset($data['customer_age']);
        unset($data['customer_gender']);

        return $data;
    }
}
