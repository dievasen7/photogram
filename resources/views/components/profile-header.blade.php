@php
if (!isset($user)) {
    $user = auth()->user();
}
@endphp
<div class="row">
    <div class="col-md-1">
        <img src="{{ $user->details->profile_photo }}" class="rounded-circle main-profile-photo" width="75" />
    </div>
    <div class="col-md-7">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $user->details->display_name }}
        </h2>
        <div class="row">
            <p>
        @if ($user->details->bio)
                {{ $user->details->bio }}<br />
        @endif
        @if($user->details->phone)
                {{ $user->details->phone }}<br />
        @endif
            </p>
        </div>
    </div>
    <div class="col-md-4 text-end">
        <a href="{{ route('following', ['user' => $user])  }}"
           title="{{ __('Who this user is following') }}"
           class="user-modal-link"
           data-empty-content="{{ __('This user still does not follow anyone') }}"
        >
            {{ __('Following') }} ({{ $user->following()->count() }})
        </a>
        <a href="{{ route('followers', ['user' => $user])  }}"
           title="{{ __('Who this user is followed by') }}"
           class="user-modal-link"
           data-empty-content="{{ __('No one is following this user yet') }}"
        >
            {{ __('Followers') }} ({{ $user->followers()->count() }})
        </a>
        @if(auth()->id() !== $user->id)
            <form method="post" action="{{ route('follow', ['user' => $user]) }}" style="display: inline">
                @csrf
                <button @class([
                    "btn",
                    "btn-primary" => !auth()->user()->following->contains($user),
                    "btn-secondary" => auth()->user()->following->contains($user)]) id="followButton">
                    @if($user->followers->contains(auth()->user()))
                        {{ __('Unfollow') }}
                    @else
                        {{ __('Follow') }}
                    @endif
                </button>
            </form>
        @endif
    </div>
</div>
