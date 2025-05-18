@if(auth()->user()->role === 'super_admin')
    <x-app-layout>
        <h2 class="text-2xl font-bold mb-6">Send User Invite</h2>

        <form action="{{ route('invites.send') }}" method="POST" class="space-y-4 max-w-lg">
            @csrf
            <input type="email" name="email" placeholder="User Email" required class="w-full border p-2 rounded" />

            <select name="school_id" required class="w-full border p-2 rounded">
                <option value="">Select School</option>
                @foreach($schools as $school)
                    <option value="{{ $school->id }}">{{ $school->name }}</option>
                @endforeach
            </select>

            <select name="role" required class="w-full border p-2 rounded">
                <option value="">Select Role</option>
                <option value="teacher">Teacher</option>
                <option value="student">Student</option>
            </select>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Send Invite</button>
        </form>
    </x-app-layout>
@endif
