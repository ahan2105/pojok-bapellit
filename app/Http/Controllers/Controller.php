<?php

namespace App\Http\Controllers;

abstract class Controller
{
    /**
     * Check if current user is admin
     */
    protected function isUserAdmin(): bool
    {
        $user = auth()->user();

        return $user && ($user->role === 'admin' || $user->is_admin === true);
    }

    /**
     * Apply user authorization to query (non-admin only see their own data)
     */
    protected function authorizeUserQuery($query)
    {
        if (! $this->isUserAdmin()) {
            $query->where('user_id', auth()->id());
        }

        return $query;
    }
}
