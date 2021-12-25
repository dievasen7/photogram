<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Listeners\RegisteredUserDetailsListener;
use App\Events\NewFollowerEvent;
use App\Listeners\NewFollowerListener;
use App\Events\NewPostEvent;
use App\Listeners\NewPostListener;
use App\Events\NewLikeEvent;
use App\Listeners\NewLikeListener;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        Event::listen(
            Registered::class,
            [RegisteredUserDetailsListener::class, 'handle']
        );
        
        Event::listen(
            NewFollowerEvent::class,
            [NewFollowerListener::class, 'handle']
        );
        
        Event::listen(
            NewPostEvent::class,
            [NewPostListener::class, 'handle']
        );
        
        Event::listen(
            NewLikeEvent::class,
            [NewLikeListener::class, 'handle']
        );
    }
}
