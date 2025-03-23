<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 dark:text-gray-900 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-900 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-900">Associate with School</h3>
                <form action="{{ route('associate.school') }}" method="POST" class="mt-4">
                    @csrf

                    <div class="form-group">
                        <label for="school_name" class="block text-sm font-medium text-gray-700">School Name</label>
                        <input type="text" name="school_name" id="school_name" class="form-control mt-1 block w-full rounded-md" value="{{ old('school_name') }}" required>
                    </div>

                    @if(auth()->user()->hasRole('student'))
                        <div class="form-group mt-4">
                            <label for="grade_level" class="block text-sm font-medium text-gray-700">Grade Level</label>
                            <input type="text" name="grade_level" id="grade_level" class="form-control mt-1 block w-full rounded-md" value="{{ old('grade_level') }}">
                        </div>
                    @elseif(auth()->user()->hasRole('teacher'))
                        <div class="form-group mt-4">
                            <label for="department" class="block text-sm font-medium text-gray-700">Department</label>
                            <input type="text" name="department" id="department" class="form-control mt-1 block w-full rounded-md" value="{{ old('department') }}">
                        </div>
                    @endif

                    <button type="submit" class="btn btn-primary mt-6">Associate School</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
