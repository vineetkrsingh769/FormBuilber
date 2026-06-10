<div x-ref="canvasArea" @dragover.prevent="onCanvasDragOver($event)" @dragleave="onCanvasDragLeave($event)" @drop="onCanvasDrop($event)"
    :class="isDragOver ? 'border-indigo-400 bg-indigo-50/50 ring-4 ring-indigo-100' : 'border-slate-200'"
    class="relative flex min-h-0 flex-1 flex-col overflow-hidden rounded-2xl border-2 border-dashed bg-white shadow-sm transition-all">

    <div class="flex shrink-0 flex-wrap items-center justify-between gap-2 border-b border-slate-100 px-4 py-3">
        <p class="text-xs font-semibold text-slate-500">
            <span x-show="fields.length === 0">Step 2 — Drag fields from the right panel →</span>
            <span x-show="fields.length > 0">Drag cards to reposition · Click ✎ to edit</span>
        </p>
        <div class="flex rounded-lg border border-slate-200 bg-slate-50 p-0.5">
            <template x-for="t in gridTemplates" :key="t.cols">
                <button @click="setGridColumns(t.cols)"
                    :class="settings.gridColumns === t.cols ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-500'"
                    class="rounded-md px-2.5 py-1 text-[10px] font-bold" x-text="`${t.cols} Col`"></button>
            </template>
        </div>
    </div>

    <div x-show="isDragOver" class="pointer-events-none absolute inset-0 z-0 grid gap-2 p-4 pt-12" :class="gridClass">
        <template x-for="i in settings.gridColumns" :key="i">
            <div class="rounded-xl border-2 border-dashed border-indigo-300/60 bg-indigo-50/40"></div>
        </template>
    </div>

    <div class="relative z-10 min-h-0 flex-1 overflow-y-auto p-4">
        <div x-show="fields.length === 0" class="flex h-full min-h-0 flex-col items-center justify-center text-center">
            <div class="mb-4 rounded-2xl bg-indigo-50 p-6">
                <svg class="h-12 w-12 text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            </div>
            <p class="font-semibold text-slate-700">Your canvas is empty</p>
            <p class="mt-1 max-w-xs text-sm text-slate-400">Drag a field from the right panel or click a field type to add it</p>
        </div>
        <div x-ref="fieldsList" x-show="fields.length > 0" class="grid gap-3" :class="gridClass">
            <template x-for="field in fields" :key="field.id">
                @include('form-builder.editor.field-card')
            </template>
        </div>
    </div>
</div>
