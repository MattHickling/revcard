@props(['value'])

<label {{ $attributes->merge([
    'style' => 'display: block; font-weight: 500; font-size: 0.875rem; color: #1f2937; background-color: #f9fafb; padding: 6px 12px; border-radius: 4px;'
]) }}>
    {{ $value ?? $slot }}
</label>
