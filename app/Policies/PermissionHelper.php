<?php

namespace App\Policies;

use App\Models\Menu;
use App\Models\User;
use Illuminate\Auth\Access\Response;

// class MenuPolicy
// {
//     /**
//      * Determine whether the user can view any models.
//      */
//     public function viewAny(User $user): bool
//     {
//         if (auth()->user()->can('view-menu')) {
//             return true;
//         }else{
//             return false;
//         }
//     }

use Illuminate\Support\Facades\Auth;

class PermissionHelper
{
    public static function canViewMenu(): bool
    {
        if (!Auth::check()) {
            return false;
        }
        $user = Auth::user();
        return $user->can('view users') || $user->can('view therapists') || $user->hasRole('therapist');
    }

    public static function canViewPermissions(): bool
    {
        return Auth::check() && Auth::user()->can('view permissions');
    }

    public static function canViewRoles(): bool
    {
        return Auth::check() && Auth::user()->can('view roles');
    }

    public static function canViewEmailConfiguration(): bool
    {
        return Auth::check() && Auth::user()->can('view-email-configuration');
    }
}
