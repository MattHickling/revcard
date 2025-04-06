
<x-guest-layout>
  <style>
      #school_search {
        padding: 10px;
        border: 2px solid #ddd;
        border-radius: 5px;
        background-color: #fff;
        color: #333;
        font-size: 16px;
        width: 100%;
        transition: border-color 0.3s ease-in-out;
      }

      #school_search:hover {
        border-color: #5c6bc0;
      }


      #school_search:focus {
        border-color: #3f51b5;
        outline: none;
      }

      #school_results {
        border: 2px solid #ddd;
        border-radius: 5px;
        background-color: #fff;
        color: #333;
        font-size: 16px;
        width: 100%;
        max-height: 200px;
        overflow-y: auto;
        position: absolute;
        z-index: 1000;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        display: none; 
      }


      .autocomplete-item {
        padding: 10px;
        cursor: pointer;
      }

      .autocomplete-item:hover {
        background-color: #f1f1f1;
      }
  </style>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- First Name -->
        <div>
            <x-input-label for="first_name" :value="__('First Name')" />
            <x-text-input id="first_name" class="block mt-1 w-full" type="text" name="first_name" :value="old('first_name')" required autofocus autocomplete="given-name" />
            <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
        </div>

        <!-- Last Name -->
        <div class="mt-4">
            <x-input-label for="last_name" :value="__('Last Name')" />
            <x-text-input id="last_name" class="block mt-1 w-full" type="text" name="last_name" :value="old('last_name')" required autocomplete="family-name" />
            <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <label for="school_search">Search for a School</label>
        <input type="text" id="school_search" class="block mt-1 w-full" placeholder="Start typing a school name...">
        <input type="hidden" id="school_id" name="school_id" />

        <!-- Dropdown results -->
        <div id="school_results" class="autocomplete-dropdown" style="display: none; position: absolute;"></div>

        <!-- Role -->
        <div class="mt-4">
            <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
            <select id="role" name="role" class="block mt-1 w-full" required>
                <option value="student">Student</option>
                <option value="teacher">Teacher</option>
                <option value="admin">Admin</option>
            </select>
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

        </div>
    </form>
    <a href="{{ route('register') }}" class="px-6 py-3 text-lg font-semibold text-black bg-green-600 rounded-lg shadow-md transition duration-300 hover:bg-green-700 focus:outline-none focus:ring-4 focus:ring-green-300 dark:focus:ring-green-600">Register</a>

</x-guest-layout>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.full.min.js"></script>

<script>
   $(document).ready(function() {
    console.log('Document is ready');
    

    $('#school_search').on('input', function() {
        var query = $(this).val();
        var searchUrl = "{{ route('search.schools') }}"; 
        
        if (query.length >= 3) {
            console.log('🟢 Searching for:', query);
            
            $.ajax({
                url: searchUrl,
                type: 'GET',
                data: { query: query },
                dataType: 'json',
                success: function(response) {
                    console.log('✅ Received data:', response);
                    var results = response.map(function(school) {
                        return '<div class="autocomplete-item" data-id="' + school.id + '">' + school.EstablishmentName + '</div>';
                    }).join('');
                    
                    $('#school_results').html(results).show();
                },
                error: function(xhr, status, error) {
                    console.log('❌ AJAX Error:', error);
                    $('#school_results').hide(); 
                }
            });
        } else {
            $('#school_results').hide(); 
        }
    });
    
    $('#school_results').on('click', '.autocomplete-item', function() {
        var schoolId = $(this).data('id');
        var schoolName = $(this).text();

        $('#school_id').val(schoolId);
        $('#school_search').val(schoolName);
        
        $('#school_results').hide();
    });

    $(document).on('click', function(e) {
        if (!$(e.target).closest('#school_search').length) {
            $('#school_results').hide();
        }
    });
});
</script>
