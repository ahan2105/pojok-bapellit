<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

abstract class Controller
{
    /**
     * Check if current user is admin
     */
    protected function isUserAdmin(): bool
    {
        $user = Auth::user();

        return $user && ($user->role === 'admin' || $user->is_admin === true);
    }

    /**
     * Apply user authorization to query (non-admin only see their own data)
     */
    protected function authorizeUserQuery(Builder $query): Builder
    {
        if (! $this->isUserAdmin()) {
            $query->where('user_id', Auth::id());
        }

        return $query;
    }
}
