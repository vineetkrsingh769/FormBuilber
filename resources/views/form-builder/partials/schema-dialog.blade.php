<x-dialog show="schemaDialogOpen" close="closeSchemaDialog" max-width="max-w-3xl">
    <x-slot:header>
        <div class="flex items-center justify-between rounded-t-2xl border-b border-slate-100 px-6 py-4">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-100 text-indigo-600">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75L22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3l-4.5 16.5" />
                    </svg>
                </div>
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-indigo-500">Form Schema</p>
                    <p class="font-bold text-slate-800" x-text="formTitle || 'Untitled Form'"></p>
                </div>
            </div>
            <button type="button" @click="closeSchemaDialog()"
                class="rounded-xl bg-slate-100 p-2 text-slate-500 transition hover:bg-slate-200"
                aria-label="Close dialog">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </x-slot:header>

    <p class="mb-3 text-sm text-slate-500">
        Includes form title, layout &amp; appearance settings, and all field edits (labels, options, required, grid width).
    </p>
    <pre class="max-h-[50vh] overflow-auto rounded-xl bg-slate-900 p-4 text-xs leading-relaxed text-emerald-400"
        x-text="schemaDialogJson"></pre>

    <x-slot:footer>
        <button type="button" @click="closeSchemaDialog()"
            class="rounded-xl border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
            Close
        </button>
        <button type="button" @click="copySchemaJson()"
            class="rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600 px-5 py-2.5 text-sm font-bold text-white shadow-md transition hover:shadow-lg">
            Copy JSON
        </button>
    </x-slot:footer>
</x-dialog>
