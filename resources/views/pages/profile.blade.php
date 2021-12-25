<x-app-layout>
    <x-slot name="header">
        @include('components/profile-header')
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200 clearfix" id="feed">
                    loading posts...
                </div>
                <div class="mt-5 row">
                    <div class="col text-center">
                        <button class="btn btn-primary" id="loadMoreBtn">{{ __('Load more') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card visually-hidden float-start" id="postTemplate" style="width: 233px; height:233px">
        <a href="#" class="post-view-link" style="overflow: hidden">
            <img src="" class="post-cover card-img-top" alt="post cover" title="">
        </a>
    </div>
    <script>
        window.onload = function() {
            let feed = document.getElementById('feed');
            let offset = 0;
            function loadFeed() {
                axios.get('{{ route('feed', ['user' => $user->id])  }}?offset=' + offset).then((response) => {
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
                            feed.appendChild(template);
                            template.classList.remove('visually-hidden');
                        }
                        if (response.data.length < {{ \App\Http\Controllers\PostController::POSTS_PER_REQUEST }}) {
                            document.getElementById('loadMoreBtn').classList.add('visually-hidden');
                        } else {
                            offset += {{ \App\Http\Controllers\PostController::POSTS_PER_REQUEST }};
                        }
                    } else if (offset === 0) {
                        feed.innerText = '{{ __('This profile has no posts') }}';
                        document.getElementById('loadMoreBtn').classList.add('visually-hidden');
                    }
                });
            }
            // Listen for click of the button and load
            document.getElementById('loadMoreBtn').addEventListener('click', loadFeed);
            loadFeed();
        }
    </script>
</x-app-layout>
