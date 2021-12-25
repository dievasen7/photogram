<?php

namespace App\Listeners;

use App\Events\NewCommentEvent;
use App\Models\UserNotification;

class NewCommentListener
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
     * @param  \App\Events\NewCommentEvent  $event
     * @return void
     */
    public function handle(NewCommentEvent $event)
    {
        $userNotification = new UserNotification([
        'link' => route('post.show',['post'=>$event->post]),
        'seen' => 0,
        'text' => view('components.user_notifications.new_comment',[
                'post'=> $event->post,
                'user'=>$event->user,
                'date'=> new \DateTimeImmutable()
            ])->render()
        ]); 
        $userNotification->user()->associate($event->post->user);
        $userNotification->save();
    }
}
