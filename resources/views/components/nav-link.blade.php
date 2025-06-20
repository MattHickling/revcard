@props(['active'])

@php
$baseStyle = '
    display: inline-flex;
    align-items: center;
    padding: 0.25rem 0.5rem;
    border-bottom: 2px solid transparent;
    font-size: 1.2rem;
    font-weight: bolder;
    line-height: 1.25rem;
    text-decoration: none;
    color: #d1d5db;
    transition: all 0.15s ease-in-out;
';

$activeStyle = '
    color: #ffffff;
    border-bottom-color: #4f46e5;
';

$style = $baseStyle . ($active ? $activeStyle : '');
@endphp

<a {{ $attributes->merge(['style' => $style]) }}>
    {{ $slot }}
</a>
