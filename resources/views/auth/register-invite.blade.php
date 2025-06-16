@if(isset($role) && $role == 'admin')
    <x-app-layout>
        <h2 class="text-2xl font-bold mb-6">Send User Invite</h2>

        <form action="{{ route('invites.send') }}" method="POST" class="space-y-4 max-w-lg">
            @csrf
        
            <input type="text" name="first_name" placeholder="First Name" required class="w-full border p-2 rounded" />
            <input type="text" name="last_name" placeholder="Last Name" required class="w-full border p-2 rounded" />
            <input type="email" name="email" placeholder="User Email" required class="w-full border p-2 rounded" />
        
            <label for="school_id">Select School</label>
            <select id="school_id" name="school_id" required class="w-full border p-2 rounded"></select>
        
            <select name="role" required class="w-full border p-2 rounded">
                <option value="">Select Role</option>
                <option value="teacher">Teacher</option>
                <option value="student">Student</option>
            </select>
        
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Send Invite</button>
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
