@props([
    'type' => 'text',
    'disabled' => false,
])

<input
    type="{{ $type }}"
    @if($disabled) disabled @endif
    {{ $attributes->merge(['class' => 'w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 shadow-sm transition focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-100']) }}
/>
