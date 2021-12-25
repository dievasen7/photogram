<x-app-layout>
    <x-slot name="header">
        @include('components/profile-header')
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200 clearfix" id="feed">
                    <h1 class="fs-1">{{ __('Create New Post') }}</h1>
                    <hr />
                    <form method="post" action="{{ route('post.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mt-3">
                                    <input type="file" name="photos[]" multiple accept="image/jpeg" class="form-control">
                                </div>

                            </div>
                            <div class="col-md-6">
                                <div class="mt-3">
                                    <label class="control-label" for="description">Description</label>
                                    <textarea class="form-control" required name="description" id="description" maxlength="255" rows="8" placeholder="Add description">{{ old('description') }}</textarea>
                                </div>
                                <div class="mt-3">
                                    <button class="btn btn-primary">{{ __('Create post') }}</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
