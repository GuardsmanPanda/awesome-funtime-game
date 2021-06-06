@props(['name'])
<svg xmlns="http://www.w3.org/2000/svg" {{ $attributes->merge(['class' => 'h-6 w-6']) }} fill="none" viewBox="0 0 24 24" stroke="currentColor">
    {!! config('icons.'.$name) !!}
</svg>