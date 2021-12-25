<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Requests\StorePostRequest;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use App\Events\NewPostEvent;

class PostController extends Controller
{
    public const POSTS_PER_REQUEST = 10;
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, ?User $user = null)
    {
        $offset = $request->query->get('offset', 0);
        if ($user) {
            $builder = $user->posts();
        } else {
            $user = auth()->user();
            $builder = Post::with('user')
                    ->whereHas('user', function(Builder $query) use ($user) {
                        $ids = $user->following->map(fn(User $user) => $user->id)->all();
                        $query->whereIn('id', $ids);
                    });
        }
        
        return $builder->with('photos')
                ->with('user.details')
                ->withCount('likes', 'comments') // likes_count & comments_count
                ->orderBy('created_at', 'desc')
                ->limit(self::POSTS_PER_REQUEST)
                ->offset($offset)
                ->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePostRequest  $request
     * @return \Illuminate\Http\Response
     */
     public function store(StorePostRequest $request)
    {
        $validated = $request->validated();
        $post = new Post();
        $post->description = strip_tags($validated['description']);
        $post->user()->associate(auth()->user());
        try {
            $post->saveOrFail();
            
            $imagesDir = '/uploads/images/' . md5(auth()->user()->email);
            $uploadDir = storage_path('app/public') . $imagesDir;
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir);
            }
            foreach ($request->file('photos') as $photo) {
                $file = $photo->store('public' . $imagesDir);
                $url = str_replace('public', '/storage', $file);
                $post->photos()->create(['url' => $url]);
            }
            
            NewPostEvent::dispatch($post);
            
            return redirect()->route('profile', ['user' => auth()->user()]);
        } catch (\Throwable $ex) {
            if ($post->id) {
                $post->delete();
            }
            
            return redirect()->back()->withErrors([$ex->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        $post->loadCount('likes');
        return view('pages.posts.show', ['post' => $post]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Post $post)
    {
        if ($request->user()->cannot('delete', $post)) {
            abort(403);
        }
        $photos = $post->photos;
        if ($post->delete()) {
            foreach ($photos as $photo) {
                $path = str_replace('/storage', '', $photo->url);
                $fullPath = storage_path('app/public') . $path;
                unlink($fullPath);
                $photo->delete();
            }
        }
        
        return response('', 204);
    }
}
