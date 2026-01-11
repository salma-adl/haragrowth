<?php

namespace App\Policies;

use App\Models\TherapistSchedule;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TherapistSchedulePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view therapist schedules') || $user->can('view therapists') || $user->hasRole('therapist');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TherapistSchedule $therapistSchedule): bool
    {
        return $user->can('view therapist schedules') || $user->can('view therapists') || $user->hasRole('therapist');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if ($user->hasRole('therapist') && !$user->hasRole('admin') && !$user->is_superuser) {
            return false;
        }
        return $user->can('create therapist schedules') || $user->can('create therapists');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TherapistSchedule $therapistSchedule): bool
    {
        if ($user->hasRole('therapist') && !$user->hasRole('admin') && !$user->is_superuser) {
            return false;
        }
        return $user->can('edit therapist schedules') || $user->can('edit therapists');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TherapistSchedule $therapistSchedule): bool
    {
        if ($user->hasRole('therapist') && !$user->hasRole('admin') && !$user->is_superuser) {
            return false;
        }
        return $user->can('delete therapist schedules') || $user->can('delete therapists');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, TherapistSchedule $therapistSchedule): bool
    {
        return $user->can('delete therapist schedules') || $user->can('delete therapists');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, TherapistSchedule $therapistSchedule): bool
    {
        return $user->can('delete therapist schedules') || $user->can('delete therapists');
    }
}
