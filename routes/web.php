<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserDetailsController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\LikesController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\CommentController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['auth', 'notif'])->group(function () {
    
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });
    
    // GET localhost:8000/p/1
    Route::get('/p/{user?}', [ProfileController::class, 'profile'])->name('profile');
    
    // GET localhost:8000/feed[/1]?offset=10
    Route::get('/feed/{user?}', [PostController::class, 'index'])->name('feed');
    
    // GET localhost:8000/post
    Route::get('/post', [PostController::class, 'create'])->name('post.create');
    
    // POST localhost:8000/post
    Route::post('/post', [PostController::class, 'store'])->name('post.store');
    
    // GET localhost:8000/post/123
    Route::get('/post/{post?}', [PostController::class, 'show'])->name('post.show');
    
    // DELETE localhost:8000/post/123
    Route::delete('/post/{post}', [PostController::class, 'destroy'])->name('post.remove');
    
    // GET localhost:8000/account
    Route::get('/account', [UserDetailsController::class, 'edit'])->name('account.edit');
    
    // POST localhost:8000/post
    Route::post('/account', [UserDetailsController::class, 'update'])->name('account.update');
    
    // GET localhost:8000/like/123
    Route::get('/like/{post}', [LikesController::class, 'index'])->name('likes.list');
    
    // POST localhost:8000/like/123
    Route::post('/like/{post}', [LikesController::class, 'update'])->name('likes.toggle');
    
    // GET localhost:8000/following/1
    Route::get('/following/{user}', [FollowController::class, 'following'])->name('following');
    
    // GET localhost:8000/followers/1
    Route::get('/followers/{user}', [FollowController::class, 'followers'])->name('followers');
    
    // POST localhost:8000/follow/1
    Route::post('/follow/{user}', [FollowController::class, 'toggleFollow'])->name('follow');
    
    // GET localhost:8000/search
    Route::get('/search', [SearchController::class, 'show'])->name('search.show');
    
    // GET localhost:8000/search/users?keywords=asdasd
    Route::get('/search/users', [SearchController::class, 'users'])->name('search.users');
    
    
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    // POST localhost:8000/comment/p/123
    Route::post('/comment/{post}', [CommentController::class, 'store'])->name('comments.store');
    
    // DELETE localhost:8000/comment/123
    Route::delete('/comment/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
});




require __DIR__.'/auth.php';
