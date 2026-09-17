<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
*/

// Channel default — biar user bisa dapet notif pribadi (opsional)
Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// ⬇️ Channel privat user — biar user dapet notif pribadi
Broadcast::channel('user.{id}', function (User $user, $id) {
    return (int) $user->id === (int) $id;
});

// Channel khusus admin bapelit
Broadcast::channel('admin.notifications', function (User $user) {
    return $user->role === 'admin' || $user->is_admin === true;
});