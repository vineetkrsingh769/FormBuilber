<footer
    x-show="activeTab === 'editor' && editorActive && settings.templateSelected"
    x-cloak
    class="editor-footer fixed bottom-0 left-0 right-0 flex items-center justify-between border-t border-slate-200 bg-white px-6 py-3 lg:left-64"
>
    <button type="button" @click="handleCancel()"
        class="rounded-xl border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 transition hover:border-slate-400 hover:bg-slate-50">
        Cancel
    </button>
    <button type="button" @click="handleNext()"
        class="rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600 px-6 py-2.5 text-sm font-bold text-white shadow-md transition hover:shadow-lg">
        Next
    </button>
</footer>
