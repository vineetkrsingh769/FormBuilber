@props(['text' => 'Section Title'])

<h3 {{ $attributes->merge(['class' => 'text-lg font-semibold text-slate-800']) }}>{{ $text }}</h3>
