<div :style="getFieldGridStyle(field)" @click="selectField(field.id)"
    :class="selectedFieldId === field.id ? 'border-indigo-400 ring-2 ring-indigo-100' : 'border-slate-200 hover:border-indigo-200'"
    class="field-card group relative cursor-pointer rounded-2xl border bg-white p-4 shadow-sm transition active:cursor-grabbing">
    <div class="absolute -right-1 -top-2 flex gap-0.5 rounded-lg border border-slate-200 bg-white p-0.5 shadow-md opacity-0 transition group-hover:opacity-100"
        :class="selectedFieldId === field.id ? 'opacity-100' : ''">
        <button @click.stop="selectField(field.id)" class="rounded p-1 text-slate-400 hover:text-indigo-600" title="Edit">
            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
        </button>
        <button @click.stop="duplicateField(field.id)" class="rounded p-1 text-slate-400 hover:text-emerald-600" title="Duplicate">⧉</button>
        <button @click.stop="requestDelete(field.id)" class="rounded p-1 text-slate-400 hover:text-rose-600" title="Delete">✕</button>
    </div>
    <div x-show="deleteConfirmId === field.id" class="absolute inset-0 z-20 flex items-center justify-center rounded-2xl bg-white/95 backdrop-blur-sm">
        <div class="text-center">
            <p class="text-sm font-bold">Remove field?</p>
            <div class="mt-3 flex gap-2">
                <button @click.stop="cancelDelete()" class="rounded-lg border px-3 py-1.5 text-xs font-semibold">Cancel</button>
                <button @click.stop="confirmDelete()" class="rounded-lg bg-rose-600 px-3 py-1.5 text-xs font-semibold text-white">Remove</button>
            </div>
        </div>
    </div>
    <span class="mb-2 inline-block rounded-md bg-slate-100 px-2 py-0.5 text-[9px] font-bold uppercase text-slate-500" x-text="getFieldMeta(field.type).label"></span>
    <x-form-fields.preview />
</div>
