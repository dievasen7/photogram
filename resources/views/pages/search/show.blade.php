<x-app-layout>
    <x-slot name="header">
        <form method="get" action="{{ route('search.users') }}" id="searchForm">
            <input type="search" class="form-control" autocomplete="off" name="keywords" placeholder="Search users by display name" />
        </form>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200 clearfix" id="searchResult">
                    loading random users...
                </div>
            </div>
        </div>
    </div>
    <div class="card visually-hidden float-start" id="userTemplate"  style="height: 435px; width: 18rem;">
        <a href="#" class="user-view-link" style="overflow: hidden">
            <img src="" class="user-profile-photo card-img-top" alt="profile photo" title="">
        </a>
        <div class="card-body">
            <h5 class="card-title user-display-name"></h5>
            <p class="card-text user-bio"></p>
            <p class="card-text user-phone">
                <small class="text-muted"></small>
            </p>
            <a href="#" class="btn btn-primary profile-view-link">{{ __('View profile') }}</a>
        </div>
    </div>
    <script>
        window.onload = function() {
            let feed = document.getElementById('searchResult');
            let offset = 0;
            function loadUsers(url, keywords) {
                if (keywords) {
                    url += '?keywords=' + keywords;
                }
                axios.get(url).then((response) => {
                    if (response.data.length) {
                        if (offset === 0) {
                            feed.innerText = '';
                        }
                        for (let i in response.data) {
                            let user = response.data[i];
                            let template = document.querySelector('#userTemplate').cloneNode(true);
                            template.id = 'user' + user.id;
                            let photo = template.querySelector('.user-profile-photo ');
                            photo.src = user.details.profile_photo;
                            photo.title = user.details.display_name;
                            template.querySelector('.profile-view-link').href = '{{ route('profile') }}/' + user.id;
                            template.querySelector('.user-view-link').href = '{{ route('profile') }}/' + user.id;
                            template.querySelector('.user-display-name').innerText = user.details.display_name;
                            template.querySelector('.user-bio').innerText = user.details.bio ? user.details.bio.substr(0, 30)+'...' : 'no bio...';
                            template.querySelector('.user-phone').innerText = user.details.phone ? user.details.phone : 'no phone...';
                            feed.appendChild(template);
                            template.classList.remove('visually-hidden');
                        }
                    } else {
                        feed.innerText = '{{ __('No users found') }}';
                        document.getElementById('loadMoreBtn').classList.add('visually-hidden');
                    }
                });
            }
            // Listen for click of the button and load
            document.getElementById('searchForm').addEventListener('submit', (event) => {
                event.preventDefault();
                event.stopPropagation();
                loadUsers(event.target.action, event.target.querySelector('input[name="keywords"]').value);
            });
            loadUsers('{{ route('search.users')  }}', false);
        }
    </script>
</x-app-layout>
