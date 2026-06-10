<header x-show="activeTab === 'editor' && editorActive" class="shrink-0 border-b border-slate-100 bg-white px-4 py-4 lg:px-8">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <button @click="switchTab('myforms')" class="rounded-xl border border-slate-200 p-2.5 text-slate-500 hover:bg-slate-50 lg:hidden" title="Back">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            </button>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-lg font-bold text-slate-900" x-text="formTitle || 'Untitled Form'"></h1>
                    <span x-show="isDirty" class="rounded-full bg-amber-100 px-2 py-0.5 text-[10px] font-bold text-amber-700">Unsaved</span>
                    <span x-show="currentFormId && !isDirty" class="rounded-full bg-emerald-100 px-2 py-0.5 text-[10px] font-bold text-emerald-700">Saved</span>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <button @click="undo()" :disabled="historyIndex <= 0"
                class="rounded-xl border border-slate-200 p-2.5 text-slate-500 hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-40" title="Undo (Ctrl+Z)">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
            </button>
            <button @click="redo()" :disabled="historyIndex >= history.length - 1"
                class="rounded-xl border border-slate-200 p-2.5 text-slate-500 hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-40" title="Redo (Ctrl+Y)">
                <svg class="h-4 w-4 -scale-x-100" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
            </button>
            <button @click="openPreview()" :disabled="fields.length === 0"
                class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 disabled:opacity-40">Preview</button>
            <button @click="handleSave()"
                class="rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600 px-5 py-2.5 text-sm font-bold text-white shadow-md hover:shadow-lg">
                Save Form
            </button>
        </div>
    </div>
</header>
