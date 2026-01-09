<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BookingPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        if (auth()->user()->can('view-booking')) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Booking $booking): bool
    {
        if (auth()->user()->can('view-booking')) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if (auth()->user()->can('create-booking')) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Booking $booking): bool
    {
        // Cek apakah user memiliki izin (permission) 'update-booking'
        if (auth()->user()->can('update-booking')) {
            return true;
        }

        // Cek apakah user memiliki izin 'update-diagnosis-booking'
        else if ($user->can('update-diagnosis-booking')) {
            // Ambil profil user
            $userProfile = $user->profile;

            // Jika profil tidak ditemukan, langsung kembalikan false
            if (!$userProfile) {
                return false;
            }

            // Cek apakah service yang terkait dengan booking ada dalam daftar service milik userProfile
            return $userProfile->services->contains('id', $booking->service_id);
        }

        // Jika tidak memenuhi kondisi di atas, akses ditolak
        else {
            return false;
        }
    }


    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Booking $booking): bool
    {
        if (auth()->user()->can('view-profile')) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Booking $booking): bool
    {
        if (auth()->user()->can('view-profile')) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Booking $booking): bool
    {
        if (auth()->user()->can('view-profile')) {
            return true;
        } else {
            return false;
        }
    }
}
