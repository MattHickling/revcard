<button {{ $attributes->merge([
    'type' => 'submit',
    'class' => '
        inline-flex items-center justify-center
        px-6 py-3
        bg-gray-800 text-white
        border border-transparent
        rounded-xl
        font-semibold text-sm
        uppercase tracking-wide
        hover:bg-white hover:text-gray-800 hover:border-gray-800
        focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500
        active:bg-gray-900
        transition duration-200 ease-in-out
        shadow-sm hover:shadow-lg
        dark:bg-gray-700 dark:hover:bg-white dark:text-white dark:hover:text-gray-900
        dark:focus:ring-offset-gray-900
    '
]) }}>
    {{ $slot }}
</button>
