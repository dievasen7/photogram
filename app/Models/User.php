<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
    
    public function details()
    {
        return $this->hasOne(UserDetails::class, 'id');
    }
    
    public function userNotifications()
    {
        return $this->hasMany(UserNotification::class);
    }
    
    public function likes()
    {
        return $this->belongsToMany(Post::class, 'likes');
    }
    
    public function comments() 
    {
        return $this->hasMany(Comment::class);
    }
    
    public function following()
    {
        return $this->belongsToMany(User::class, 'following', 'follower_id', 'followed_id');
    }
    
    public function followers()
    {
        return $this->belongsToMany(User::class, 'following', 'followed_id', 'follower_id');
    }
    
    public function userNotificationsUnread()
    {
        return $this->userNotifications()->where('seen', '=', 0)->get();
    }
    
    public function userNotificationsUnreadCount()
    {
        return $this->userNotifications()->where('seen', '=', 0)->count();
    }
}
