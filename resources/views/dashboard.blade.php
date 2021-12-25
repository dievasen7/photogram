<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200" id="feed">
                    Loading posts...
                </div>
                <div class="mt-5 row">
                    <div class="col text-center">
                        <button class="btn btn-primary" id="loadMoreBtn">{{ __('Load more') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card mb-3 visually-hidden" style="width: 100%" id="postTemplate">
        <div class="row g-0">
            <div class="col-md-6 post-images">
                <img src="" style="max-height: 300px" class="post-cover img-fluid rounded-start" alt="{{ __('post cover') }}">
            </div>
            <div class="col-md-6">
                <div class="card-body">
                    <h5 class="card-title">
                        <a href="#" class="post-author"></a>
                    </h5>
                    <p class="card-text post-description"></p>
                    <p class="card-text post-details">
                        <span class="post-created"></span>,
                        <span class="comments-count"></span> {{ __('comments') }},
                        <span class="likes-count"></span> {{ __('Like') }}
                    </p>
                    <p class="card-text post-actions">
                        <a href="#" class="text-primary post-view-link">{{ __('View') }}</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <script>
        window.onload = function() {
            let feed = document.getElementById('feed');
            let offset = 0;
            function loadFeed() {
                axios.get('{{ route('feed')  }}?offset=' + offset).then((response) => {
                    if (response.data.length) {
                        if (offset === 0) {
                            feed.innerText = '';
                        }
                        for (let i in response.data) {
                            let post = response.data[i];
                            let template = document.querySelector('#postTemplate').cloneNode(true);
                            template.id = 'post' + post.id;
                            template.querySelector('.post-cover').src = post.photos[0].url;
                            template.querySelector('.post-cover').title = post.description;
                            template.querySelector('.post-view-link').href = '{{ route('post.show') }}/' + post.id;
                            template.querySelector('.post-author').innerText = post.user.details.display_name;
                            template.querySelector('.post-author').href = '{{ route('profile') }}/' + post.user.id;
                            template.querySelector('.post-description').innerText = post.description;
                            let created_at = new Date(post.created_at);
                            template.querySelector('.post-created').innerText = created_at.getDate() + '.' + created_at.getMonth() + '.' + created_at.getFullYear();
                            template.querySelector('.comments-count').innerText = post.comments_count;
                            template.querySelector('.likes-count').innerText = post.likes_count;
                            feed.appendChild(template);
                            template.classList.remove('visually-hidden');
                        }
                        if (response.data.length < {{ \App\Http\Controllers\PostController::POSTS_PER_REQUEST }}) {
                            document.getElementById('loadMoreBtn').classList.add('visually-hidden');
                        } else {
                            offset += {{ \App\Http\Controllers\PostController::POSTS_PER_REQUEST }};
                        }
                    } else if (offset === 0) {
                        feed.innerHTML = '{{ __('There are no items in your feed. Try following some users to see their posts here.') }}';
                        document.getElementById('loadMoreBtn').classList.add('visually-hidden');
                    }
                }).catch(() => {
                    feed.innerHTML = '<div class="alert alert-danger"><p>{{ __('Something went wrong, please try again later') }}</p></div>';
                });
            }
            // Listen for click of the button and load
            document.getElementById('loadMoreBtn').addEventListener('click', loadFeed);
            loadFeed();
        }
    </script>
</x-app-layout>
