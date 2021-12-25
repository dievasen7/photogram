<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Events\NewLikeEvent;

class LikesController extends Controller
{
    
    public function update(Post $post)
    {
        $post->likes()->toggle(auth()->user());
        
        if ($post->likes->contains(auth()->user())) {
            NewLikeEvent::dispatch($post, auth()->user());
        }
        
        return [
            'likes_count' => $post->likes()->count(),
            'label' => $post->likes->contains(auth()->user()) ? __('Dislike') : __('Like'),
        ];
    }
    
    public function index(Post $post)
    {
        return $post->likes()->with('details')->get();
    }
}
