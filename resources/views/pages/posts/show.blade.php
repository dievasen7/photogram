<x-app-layout>
    <x-slot name="header">
        @include('components/profile-header')
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200 clearfix" id="feed">
                    <div class="row">
                        <div class="col-md-8">
                            <div id="postCarousel" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-inner">
                                    @foreach($post->photos as $photo)
                                        <div @class(["carousel-item", 'active' => $loop->index === 0])>
                                            <img src="{{ $photo->url }}" class="d-block w-100" alt="{{ __('Post photo') }}">
                                        </div>
                                    @endforeach
                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#postCarousel" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#postCarousel" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col">
                                    <p class="fs-6">{{ $post->description }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <p>{{ $post->created_at->format('d.m.Y, H:i') }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 m-3">
                                    <p>
                                        <a href="{{ route('likes.toggle', ['post' => $post]) }}" id="postLike" class="text-success">
                                            @if($post->likes->contains(auth()->user()))
                                                {{ __('Dislike') }}
                                            @else
                                                {{ __('Like') }}
                                            @endif
                                        </a>
                                        <a href="{{ route('likes.list', ['post' => $post]) }}"
                                           data-empty-content="{{ __('No one still likes this post') }}"
                                           id="postLikeCounter"
                                           class="text-success user-modal-link"
                                        >
                                            ({{ $post->likes_count }})
                                        </a>
                                    </p>
                                </div>
                                @if($post->user->id === auth()->id())
                                <div class="col-md-3 m-3">
                                    <p>
                                        <a href="{{ route('post.remove', ['post' => $post]) }}" id="postRemove" class="text-danger">
                                            {{ __('Delete') }}
                                        </a>
                                    </p>
                                </div>
                                @endif
                            </div>
                            <hr />
                            <div id="comments" class="row">
                                <div class="col mt-3" id="comments">
                                    @if($post->comments()->count())
                                        <ul class="list-group">
                                            @foreach ($post->comments->reverse() as $comment)
                                            <li class="list-group-item" id="comment{{$comment->id}}">
                                                <div class="card mb-3">
                                                    <div class="row g-0">
                                                        <div class="col-md-4">
                                                            <img src="{{ $comment->user->details->profile_photo }}" alt="comment author" />
                                                        </div>
                                                        <div class="col-md-8">
                                                            <div class="card-body">
                                                                <h5 class="card-title">{{ $comment->user->details->display_name }}</h5>
                                                                <p class="card-text">{{ $comment->text }}</p>
                                                                <p class="card-text">
                                                                    <small class="text-muted">
                                                                        {{ $comment->created_at->format('H:i, d.m.Y') }}
                                                                    </small>
                                                                </p>
                                                                @if (auth()->id() === $comment->user_id || auth()->id() === $post->user_id)
                                                                    <p class="card-text">
                                                                        <a href="{{ route('comments.destroy', ['comment' => $comment]) }}" class="delete-comment text-danger" data-comment-id="{{ $comment->id }}">
                                                                            {{ __('Delete comment') }}
                                                                        </a>
                                                        </p>
                                                 @endif</div></div>                                                  
                                                    </div>
                                               </div>
                                    </li>                                        
                                    @endforeach</ul>                    
                                    @else
                                    <p class="text-muted">{{ __('No comments for this post') }}</p>
                                    @endif
                                </div>
                            </div>
                            <hr />
                            <div class="row">
                                <div class="col mt-3" id="commentsForm">
                                    <p>{{ __('Write comment') }}</p>
                                    <form method="post" action="{{ route('comments.store', ['post' => $post]) }}">
                                        @csrf
                                        <textarea class="form-control" name="text"></textarea>
                                        <div class="mt-1 text-end">
                                            <button class="btn btn-primary">{{ __('Submit') }}</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <script>
            window.onload = function() {
            @if($post->user->id === auth()->id())
                document.getElementById('postRemove').addEventListener('click', (event) => {
                    event.preventDefault();
                    event.stopPropagation();
                    let confirm = window.confirm('{{ __('Are you sure you want to remove this post?') }}');
                    if (confirm) {
                        axios.delete(event.target.href).then((response) => {
                            if (response.status === 204) {
                                window.location.href = '{{ route('profile', ['user' => auth()->user()]) }}';
                            } else {
                                window.alert('{{ __('There was an error while deleting the post') }}');
                            }
                        }).catch(() => {
                            window.alert('Cannot delete post');
                        });
                    }
                });
 @endif
                document.getElementById('postLike').addEventListener('click', (event) => {
                    event.preventDefault();
                    event.stopPropagation();
                    axios.post(event.target.href).then((response) => {
                        if (response.status === 200) {
                            let count = response.data.likes_count;
                            document.getElementById('postLikeCounter').innerHTML = '('+count+')';
                            event.target.innerText = response.data.label;
                        }
                    });
                });
                let deleteLinks = document.querySelectorAll('.delete-comment');
                let delLinksLength = deleteLinks.length;
                for (let i = 0; i < delLinksLength; i++) {
                    let link = deleteLinks[i];
                    link.addEventListener('click', (event) => {
                        event.preventDefault();
                        event.stopPropagation();
                        let confirm = window.confirm('{{ __('Are you sure you want to remove this comment?') }}');
                        if (confirm) {
                            axios.delete(event.target.href).then((response) => {
                                if (response.status === 200 && response.data.result) {
                                    document.querySelector('#comment' + event.target.dataset.commentId).remove();
                                }
                            }).catch(() => { window.alert('Error while deleting the comment'); });
                        }
                    });
                }
            };
        </script>
       
    </div>

</x-app-layout>