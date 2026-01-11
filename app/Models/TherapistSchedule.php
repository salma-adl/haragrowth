<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TherapistSchedule extends Model
{
    protected $fillable = [
        'therapist_id',
        'available_date',
        'start_time',
        'end_time',
    ];

    public function therapist()
    {
        return $this->belongsTo(User::class, 'therapist_id');
    }
}
