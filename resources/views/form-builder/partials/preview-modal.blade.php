<div x-show="previewMode" x-cloak x-transition.opacity
    class="modal-overlay flex items-start justify-center overflow-y-auto bg-slate-900/70 p-4 backdrop-blur-md sm:p-8"
    @keydown.escape.window="closePreview()">
    <div @click.outside="closePreview()" class="relative my-4 w-full rounded-3xl bg-white shadow-2xl" :class="activePreviewWidth.class" :style="activePreviewWidth.style ?? ''">
        <div class="sticky top-0 z-10 flex items-center justify-between rounded-t-3xl border-b border-slate-100 bg-white/95 px-6 py-4 backdrop-blur-md">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-100 text-indigo-600">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </div>
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-indigo-500">Live Preview</p>
                    <p class="font-bold text-slate-800" x-text="activePreviewTitle"></p>
                </div>
            </div>
            <div class="flex gap-2">
                <button @click="resetPreview()" class="rounded-xl px-3 py-2 text-xs font-semibold text-slate-500 hover:bg-slate-100">Reset</button>
                <button @click="closePreview()" class="rounded-xl bg-slate-100 p-2 text-slate-500 hover:bg-slate-200">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>
        <div class="px-6 py-8 sm:px-10">
            <div x-show="previewSubmitted && activePreviewSettings.showSuccessMessage" class="py-20 text-center">
                <div class="mx-auto mb-5 flex h-20 w-20 items-center justify-center rounded-full bg-emerald-100 text-4xl text-emerald-600">✓</div>
                <h3 class="text-2xl font-bold text-slate-900">Submitted!</h3>
                <p class="mt-2 text-slate-500" x-text="activePreviewSettings.successMessage"></p>
                <button @click="resetPreview()" class="mt-8 rounded-xl bg-slate-900 px-8 py-3 text-sm font-semibold text-white hover:bg-slate-800">Fill Again</button>
            </div>
            <div x-show="previewSubmitted && !activePreviewSettings.showSuccessMessage" class="py-20 text-center">
                <p class="text-lg font-semibold text-slate-700">Form submitted.</p>
                <button @click="resetPreview()" class="mt-6 rounded-xl border border-slate-200 px-6 py-2.5 text-sm font-semibold text-slate-600">Fill Again</button>
            </div>
            <div x-show="!previewSubmitted">
                <div x-show="activePreviewSettings.showFormTitle || (activePreviewSettings.showDescription && activePreviewSettings.description)" class="mb-8 text-center sm:text-left">
                    <h2 x-show="activePreviewSettings.showFormTitle" class="text-3xl font-bold text-slate-900" x-text="activePreviewTitle"></h2>
                    <p x-show="activePreviewSettings.showDescription && activePreviewSettings.description" class="mt-2 text-slate-500" x-text="activePreviewSettings.description"></p>
                </div>
                <form @submit.prevent="handlePreviewSubmit()">
                    <div class="grid gap-5" :class="getGridClassFor(activePreviewSettings.gridColumns)">
                        <template x-for="field in activePreviewFields" :key="field.id">
                            <div :style="getPreviewGridStyle(field)"><x-form-fields.live /></div>
                        </template>
                    </div>
                    <div x-show="activePreviewFields.length > 0 && (activePreviewSettings.showSubmitButton || activePreviewSettings.showResetButton)" class="mt-8 flex flex-wrap gap-3">
                        <button x-show="activePreviewSettings.showSubmitButton" type="submit" class="rounded-xl px-8 py-3.5 text-sm font-bold text-white shadow-lg" :class="activePreviewTheme.class" :style="activePreviewTheme.style ?? ''" x-text="activePreviewSettings.submitLabel"></button>
                        <button x-show="activePreviewSettings.showResetButton" type="button" @click="resetPreview()" class="rounded-xl border border-slate-200 px-6 py-3.5 text-sm font-semibold text-slate-600 hover:bg-slate-50" x-text="activePreviewSettings.resetLabel"></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
