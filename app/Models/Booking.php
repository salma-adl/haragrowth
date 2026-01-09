<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = ['booking_code', 'customer_id', 'user_profile_id', 'service_id', 'booking_date', 'start_time', 'end_time', 'status', 'notes', 'therapist_notes', 'diagnosis', 'recommendation', 'schedule_id'];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function userProfile()
    {
        return $this->belongsTo(UserProfile::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }
}
