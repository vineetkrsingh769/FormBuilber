@props([
    'show' => 'dialogOpen',
    'close' => 'closeDialog',
    'maxWidth' => 'max-w-lg',
    'title' => null,
])

<div
    x-show="{{ $show }}"
    x-cloak
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="modal-overlay flex items-center justify-center overflow-y-auto bg-slate-900/70 p-4 backdrop-blur-md"
    @keydown.escape.window="{{ $close }}()"
    role="dialog"
    aria-modal="true"
>
    <div
        @click.outside="{{ $close }}()"
        {{ $attributes->merge(['class' => "relative w-full {$maxWidth} rounded-2xl bg-white shadow-2xl"]) }}
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
    >
        @isset($header)
            {{ $header }}
        @elseif($title)
            <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
                <h2 class="text-lg font-bold text-slate-900">{{ $title }}</h2>
                <button type="button" @click="{{ $close }}()"
                    class="rounded-xl bg-slate-100 p-2 text-slate-500 transition hover:bg-slate-200"
                    aria-label="Close dialog">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        @endif

        <div class="px-6 py-5">
            {{ $slot }}
        </div>

        @isset($footer)
            <div class="flex flex-wrap items-center justify-end gap-2 border-t border-slate-100 px-6 py-4">
                {{ $footer }}
            </div>
        @endisset
    </div>
</div>
