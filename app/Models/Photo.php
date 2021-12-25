<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;
        
    public $timestamps = false;
    
    protected $fillable = [
        'url',
    ];
    
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
