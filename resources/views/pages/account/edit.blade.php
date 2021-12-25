<x-app-layout>
    <x-slot name="header">
        @include('components/profile-header')
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200 clearfix" id="feed">
                    <div class="row">
                        <div class="col">
                            <h1 class="fs-1">{{ __('Edit Account Details') }}</h1>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8 offset-2">
                            <form method="post" enctype="multipart/form-data" action="{{ route('account.update') }}">
                                @csrf
                                <div class="mt-3">
                                    <label for="profilePhoto" class="form-label">{{ __('Profile photo') }}</label>
                                    <input type="file" class="form-control" name="profile_photo">
                                </div>
                                <div class="mt-3">
                                    <label for="displayName" class="form-label">{{ __('Display name') }}</label>
                                    <input type="text" value="{{ old('display_name', $details->display_name) }}" class="form-control" name="display_name">
                                </div>
                                <div class="mt-3">
                                    <label for="bio" class="form-label">{{ __('Bio') }}</label>
                                    <input type="text" value="{{ old('bio', $details->bio) }}" class="form-control" name="bio">
                                </div>
                                <div class="mt-3">
                                    <label for="phone" class="form-label">{{ __('Phone') }}</label>
                                    <input type="text" value="{{ old('phone', $details->phone) }}" class="form-control" name="phone">
                                </div>
                                <div class="mt-3 text-center">
                                    <button type="reset" class="btn btn-secondary">{{ __('Reset') }}</button>
                                    <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
