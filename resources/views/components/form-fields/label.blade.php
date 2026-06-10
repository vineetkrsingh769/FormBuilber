@props(['required' => false])

<label {{ $attributes->merge(['class' => 'mb-1.5 block text-sm font-medium text-slate-700']) }}>
    {{ $slot }}
    @if($required)
        <span class="text-rose-500">*</span>
    @endif
</label>
