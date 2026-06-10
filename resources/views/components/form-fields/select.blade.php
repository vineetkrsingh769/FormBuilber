@props([
    'options' => [],
    'disabled' => false,
])

<select
    @if($disabled) disabled @endif
    {{ $attributes->merge(['class' => 'w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800 shadow-sm transition focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-100']) }}
>
    {{ $slot }}
    @foreach($options as $option)
        <option value="{{ $option }}">{{ $option }}</option>
    @endforeach
</select>
