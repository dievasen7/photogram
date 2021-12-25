<?php

namespace App\Listeners;

use App\Events\NewPostEvent;
use App\Models\UserNotification;

class NewPostListener
{

    /**
     * Handle the event.
     *
     * @param  \App\Events\NewPostEvent  $event
     * @return void
     */
    public function handle(NewPostEvent $event)
    {
        $post = $event->post;
        $link = route('post.show', ['post' => $post]);
        $seen = 0;
        $text = view('components.user_notifications.new_post', [
            'post' => $post, 
            'date' => new \DateTimeImmutable()
        ])->render();
        foreach ($post->user->followers as $follower) {
            $userNotification = new UserNotification([
                'link' => $link,
                'seen' => $seen,
                'text' => $text
            ]);
            $userNotification->user()->associate($follower);
            $userNotification->save();
        }
        
    }
}
