<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy
{
    use HandlesAuthorization;

 
    /**
     * The user, who created the comment can delete it.
     * The user, whose post has been commented can delete the comment
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Comment $comment)
    {
        //
        return $comment->user_id === $user->id || $comment->post->user->id === $user->id;
    }

}