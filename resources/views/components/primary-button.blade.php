<button {{ $attributes->merge([
    'type' => 'submit',
    'class' => '
        inline-flex items-center justify-center
        px-8 py-4
        bg-blue-500 text-black
        border border-transparent
        rounded-2xl
        font-semibold text-base
        uppercase tracking-wider
        hover:bg-white hover:border-blue-500
        focus:outline-none focus:ring-4 focus:ring-blue-300 focus:ring-offset-2
        active:bg-blue-600
        transition duration-200 ease-in-out
        shadow-md hover:shadow-xl
        dark:bg-blue-500 dark:hover:bg-white dark:text-black dark:hover:text-blue-900
        dark:focus:ring-offset-gray-900
    '
]) }}>
    {{ $slot }}
</button>
