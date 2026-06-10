<div x-show="activeTab === 'editor' && editorActive" x-cloak
    :class="settings.templateSelected ? 'editor-with-footer' : ''"
    class="flex min-h-0 flex-1 flex-col overflow-hidden lg:flex-row">
    <div class="flex min-h-0 flex-1 flex-col overflow-hidden p-4 lg:p-6 lg:pr-3">
        @include('form-builder.editor.template-picker')

        <div x-show="settings.templateSelected" class="flex min-h-0 flex-1 flex-col">
            <div class="mb-3 shrink-0 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <input type="text" x-model="formTitle" @input="onFieldChange()" maxlength="200"
                    class="w-full border-0 bg-transparent text-xl font-bold text-slate-900 placeholder-slate-300 focus:outline-none"
                    placeholder="Name your form..." />
                <p class="mt-1 text-xs text-slate-400" x-text="`${titleLength}/200 characters`"></p>
            </div>
            @include('form-builder.editor.canvas')
        </div>
    </div>
    @include('form-builder.editor.palette')
</div>
