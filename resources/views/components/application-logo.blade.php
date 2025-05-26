@php
    $height = isset($height) ? $height : '50';
@endphp
<img src="{{ asset('images/UNTAD.png') }}" alt="UNTAD Logo" {{ $attributes->merge(['class' => 'h-' . $height]) }}>
