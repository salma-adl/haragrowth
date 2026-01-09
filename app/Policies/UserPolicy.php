<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        if (auth()->user()->can('view users')) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        if (auth()->user()->can('view users')) {
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
        if (auth()->user()->can('view users')) {
            return true;
        } else {
            return false;
        }
    }

    public function update(User $user, User $model): bool
    {
        // Bisa update diri sendiri atau jika punya permission
        return $user->can('edit-full-user') ? true : $user->id === $model->id && $user->can('edit users');
    }


    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        if (auth()->user()->can('delete users')) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        if (auth()->user()->can('delete users')) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        if (auth()->user()->can('delete users')) {
            return true;
        } else {
            return false;
        }
    }
}
