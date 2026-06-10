@props(['text' => 'Description text'])

<p {{ $attributes->merge(['class' => 'text-sm leading-relaxed text-slate-500']) }}>{{ $text }}</p>
