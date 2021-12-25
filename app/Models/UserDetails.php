<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDetails extends Model
{
    use HasFactory;
    
    public $timestamps = false;
    
    protected $fillable = [
        'display_name',
        'bio',
        'profile_photo',
        'phone',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'id');
    }
}
