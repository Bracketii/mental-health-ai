<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class RoleHelpers
{
    /**
     * Check if the authenticated user is an admin.
     *
     * @return bool
     */
    public static function isAdmin(): bool
    {
        return Auth::check() && Auth::user()->isAdmin();
    }

    /**
     * Check if the authenticated user has a specific role.
     *
     * @param string $role
     * @return bool
     */
    public static function hasRole(string $role): bool
    {
        return Auth::check() && Auth::user()->hasRole($role);
    }
}
