
@if ($errors->any())
    <div style="background-color: #fee2e2; border: 1px solid #f87171; color: #b91c1c; padding: 1rem; border-radius: 0.375rem;">
        <ul style="list-style-type: disc; padding-left: 1.25rem;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


@if(isset($role) && $role == 'admin')
    <x-app-layout>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <h2 class="text-2xl font-bold mb-6">Send User Invite</h2>

        <form action="{{ route('invites.send') }}" method="POST" class="space-y-6 max-w-lg mx-auto bg-white p-6 rounded-lg shadow-md">
            @csrf
        
                   <h2 style="font-size: 1.5rem; font-weight: bolder; margin-bottom: 0.5rem;"><b>Send User Invite</b></h2>

        
            {{-- First and Last Name --}}
            <input
                type="text"
                name="first_name"
                placeholder="First Name"
                required
                value="{{ old('first_name') }}"
                class="w-1/2 border border-gray-300 p-3 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
            />

            <input
                type="text"
                name="last_name"
                placeholder="Last Name"
                required
                value="{{ old('last_name') }}"
                class="w-1/2 border border-gray-300 p-3 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
            />
        
            {{-- Email --}}
            <input
                type="email"
                name="email"
                placeholder="User Email"
                required
                value="{{ old('email') }}"
                class="w-full border border-gray-300 p-3 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
            />
        
            {{-- Password --}}

        
            {{-- School Select --}}
            <div>
                <label for="school_id" class="block text-sm font-semibold text-gray-700 mb-1">Select School</label>
                <select
                    id="school_id"
                    name="school_id"
                    required
                    class="w-full border border-gray-300 p-3 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
                ></select>
            </div>
        
            {{-- Role Select --}}
            <select
                name="role"
                required
                class="w-full border border-gray-300 p-3 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
            >
                <option value="">Select Role</option>
                <option value="teacher">Teacher</option>
                <option value="student">Student</option>
            </select>
        
            {{-- Submit Button --}}
            <button
                type="submit"
                class="font-semibold px-6 py-3 rounded-md shadow-md transition duration-200 focus:outline-none"
                style="
                    background-color: #60a5fa; /* blue-400 */
                    color: #ffffff;
                    border: none;
                    cursor: pointer;
                "
                onmouseover="this.style.backgroundColor='#3b82f6'"  
                onmouseout="this.style.backgroundColor='#60a5fa'"
            >
                Send Invite
            </button>

        </form>
        
        
        
    </x-app-layout>
@endif

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

<script>
  const schoolSelect = document.getElementById('school_id');
  const choices = new Choices(schoolSelect, {
    searchEnabled: true,
    placeholderValue: 'Search for a school',
    removeItemButton: true,
  });


const schoolSearchUrl = "{{ url('/ajax/search-schools') }}";

schoolSelect.addEventListener('search', function(event) {
    const searchTerm = event.detail.value;

    if (!searchTerm || searchTerm.length < 2) {
      return;
    }

    fetch(`${schoolSearchUrl}?query=${encodeURIComponent(searchTerm)}`)
      .then(response => response.json())
      .then(data => {
        choices.clearChoices();

        const formattedChoices = data.map(school => ({
          value: school.id,
          label: school.EstablishmentName
        }));

        choices.setChoices(formattedChoices, 'value', 'label', true);
      })
      .catch(err => {
        console.error('Failed to fetch schools:', err);
      });
});

</script>
