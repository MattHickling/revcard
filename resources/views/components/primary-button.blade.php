<button {{ $attributes->merge([
    'type' => 'submit',
    'class' => '
        inline-flex items-center justify-center
        px-6 py-3
        bg-blue-700 text-white
        border border-transparent
        rounded-xl
        font-semibold text-sm
        uppercase tracking-wide
        hover:bg-white hover:text-blue-700 hover:border-blue-700
        focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500
        active:bg-blue-800
        transition duration-200 ease-in-out
        shadow-sm hover:shadow-lg
        dark:bg-blue-600 dark:hover:bg-white dark:text-white dark:hover:text-blue-900
        dark:focus:ring-offset-gray-900
    '
]) }}>
    {{ $slot }}
</button>

