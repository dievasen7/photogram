<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\UserNotification;

class UserNotificationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->query->has('notif')) {
            $id = $request->query->getInt('notif');
            $notification = UserNotification::find($id);
            if ($notification && $notification->user_id === (int) auth()->id()) {
                $notification->seen = 1;
                $notification->save();
            }
        }
        return $next($request);
    }
}
