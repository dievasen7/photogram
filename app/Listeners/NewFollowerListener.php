<?php

namespace App\Listeners;

use App\Events\NewFollowerEvent;
use App\Models\UserNotification;

class NewFollowerListener
{

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(NewFollowerEvent $event)
    {
        $userNotification = new UserNotification();
        $userNotification->link = route('profile', ['user' => $event->follower]);
        $userNotification->seen = 0;
        $userNotification->user()->associate($event->followed);
        $userNotification->text = view('components.user_notifications.new_follower', [
            'follower' => $event->follower,
            'date' => new \DateTimeImmutable(),
        ])->render();
        
        $userNotification->save();
    }
}
