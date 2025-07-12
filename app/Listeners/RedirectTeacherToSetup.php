<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class RedirectTeacherToSetup
{
    /**
     * Handle the Registered event.
     *
     * @param  \Illuminate\Auth\Events\Registered  $event
     * @return \Illuminate\Http\RedirectResponse|null
     */
    public function handle(Registered $event)
    {
        $user = $event->user;

        Log::info('Checking user role for redirection', [
            'user_id' => $user->id,
            'email' => $user->email,
            'role_name' => $user->role_name,
            'role_id' => $user->role_id,
        ]);

        if ($user->role_name === 'teacher') {
            Log::info('Redirecting teacher to profile setup', [
                'user_id' => $user->id,
                'email' => $user->email,
            ]);
            return new RedirectResponse(route('teacher.profile.create'));
        }

        Log::info('No redirection for non-teacher, proceeding to default', [
            'user_id' => $user->id,
            'email' => $user->email,
        ]);

        return null;
    }
}