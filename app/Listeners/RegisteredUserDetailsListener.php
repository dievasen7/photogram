<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\UserDetails;

class RegisteredUserDetailsListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \Illuminate\Auth\Events\Registered  $event
     * @return void
     */
    public function handle(Registered $event)
    {
        $user = $event->user;
        $names = explode(' ', $user->name);
        $displayName = sprintf('%s. %s', mb_substr($names[0], 0, 1), $names[1]);
        $userDetails = new UserDetails([
            'display_name' => $displayName,
            'profile_photo' => 'https://icon-library.com/images/141782.svg.svg',
            'bio' => '',
            'phone' => ''
        ]);
        $userDetails->id = $user->id;
        $userDetails->save();
    }
}
