<div x-show="activeTab === 'myforms'" x-cloak class="min-h-0 flex-1 overflow-y-auto p-4 lg:p-8">
    <div class="mx-auto max-w-5xl">
        <div class="mb-8 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-slate-900">My Forms</h2>
                <p class="mt-1 text-sm text-slate-500">Your saved forms live here. Preview anytime or open in the editor.</p>
            </div>
            <button @click="createNewForm()" class="rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-bold text-white shadow-md hover:bg-indigo-700">+ New Form</button>
        </div>

        {{-- Welcome for new users --}}
        <div x-show="!editorActive && savedForms.length === 0" class="mb-8 rounded-3xl border border-indigo-100 bg-gradient-to-br from-indigo-50 to-violet-50 p-8 text-center lg:p-12">
            <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-white text-3xl shadow-sm">👋</div>
            <h2 class="text-2xl font-bold text-slate-900">Welcome to FormCraft</h2>
            <p class="mx-auto mt-3 max-w-md text-sm leading-relaxed text-slate-600">
                Create beautiful forms with drag & drop. Click <strong>New Form</strong> to start — you'll pick a layout, add fields, then save here in My Forms.
            </p>
            <button @click="createNewForm()" class="mt-6 inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600 px-8 py-3.5 text-sm font-bold text-white shadow-lg hover:shadow-xl">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                Create Your First Form
            </button>
        </div>

        <div x-show="savedForms.length === 0 && editorActive" class="rounded-3xl border-2 border-dashed border-slate-200 py-16 text-center">
            <p class="text-4xl">📋</p>
            <p class="mt-4 text-lg font-bold text-slate-700">No saved forms yet</p>
            <p class="mt-2 text-sm text-slate-400">Finish building, then click <strong>Save Form</strong> in the editor</p>
        </div>

        <div x-show="savedForms.length > 0" class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
            <template x-for="form in savedForms" :key="form.id">
                <div @click="loadFormForEdit(form.id)"
                    class="group relative flex cursor-pointer flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:border-indigo-200 hover:shadow-lg">
                    <div class="flex items-start justify-between gap-2 border-b border-slate-100 bg-slate-50/80 px-4 py-3 transition group-hover:bg-indigo-50/40">
                        <div class="min-w-0 flex-1">
                            <h3 class="truncate text-sm font-bold text-slate-900 group-hover:text-indigo-700" x-text="form.formTitle"></h3>
                            <p class="mt-0.5 text-[10px] text-slate-400" x-text="formatDate(form.updatedAt)"></p>
                        </div>
                        <div class="relative shrink-0" @click.stop @click.outside="closeFormMenu()">
                            <button type="button" @click.stop="toggleFormMenu(form.id)" title="More options"
                                :class="formMenuOpenId === form.id ? 'bg-white text-slate-700 shadow-sm' : 'text-slate-500 hover:bg-white hover:text-slate-700 hover:shadow-sm'"
                                class="flex h-8 w-8 items-center justify-center rounded-lg transition">
                                @include('form-builder.partials.icon', ['name' => 'more-dots', 'class' => 'h-5 w-5'])
                            </button>
                            <div x-show="formMenuOpenId === form.id" x-cloak
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="opacity-0 scale-95"
                                x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="opacity-100 scale-100"
                                x-transition:leave-end="opacity-0 scale-95"
                                class="absolute right-0 top-full z-30 mt-1 min-w-[10.5rem] overflow-hidden rounded-xl border border-slate-200 bg-white py-1 shadow-lg">
                                <button type="button" @click="previewSavedForm(form.id)"
                                    class="flex w-full items-center gap-2.5 px-3 py-2 text-left text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                                    @include('form-builder.partials.icon', ['name' => 'preview', 'class' => 'h-4 w-4 text-slate-500'])
                                    <span>Preview</span>
                                </button>
                                <button type="button" @click="loadFormForEdit(form.id)"
                                    class="flex w-full items-center gap-2.5 px-3 py-2 text-left text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                                    @include('form-builder.partials.icon', ['name' => 'edit', 'class' => 'h-4 w-4 text-slate-500'])
                                    <span>Edit</span>
                                </button>
                                <div class="my-1 border-t border-slate-100"></div>
                                <button type="button" @click="deleteSavedForm(form.id)"
                                    class="flex w-full items-center gap-2.5 px-3 py-2 text-left text-sm font-medium text-rose-600 transition hover:bg-rose-50">
                                    @include('form-builder.partials.icon', ['name' => 'delete', 'class' => 'h-4 w-4'])
                                    <span>Delete</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="relative flex-1 p-4">
                        <div class="saved-form-preview pointer-events-none select-none">
                            <p x-show="form.settings?.showFormTitle !== false" class="text-base font-bold text-slate-900" x-text="form.formTitle"></p>
                            <p x-show="form.settings?.showDescription !== false && form.settings?.description" class="mt-1 text-xs text-slate-500" x-text="form.settings.description"></p>
                            <div x-show="(form.fields?.length ?? 0) === 0" class="mt-6 rounded-xl border border-dashed border-slate-200 py-8 text-center text-xs text-slate-400">
                                No fields yet
                            </div>
                            <div x-show="(form.fields?.length ?? 0) > 0" class="mt-4 grid gap-2.5" :class="getGridClassFor(form.settings?.gridColumns ?? 1)">
                                <template x-for="field in form.fields" :key="field.id">
                                    <div :style="getSavedFormGridStyle(field, form)">
                                        <x-form-fields.preview />
                                    </div>
                                </template>
                            </div>
                            <button x-show="(form.fields?.length ?? 0) > 0 && form.settings?.showSubmitButton !== false" type="button" tabindex="-1"
                                class="mt-4 w-full rounded-lg py-2 text-xs font-bold text-white"
                                :class="getThemeForSettings(form.settings).class"
                                :style="getThemeForSettings(form.settings).style ?? ''"
                                x-text="form.settings?.submitLabel ?? 'Submit'"></button>
                        </div>
                        <div x-show="(form.fields?.length ?? 0) > 2" class="pointer-events-none absolute inset-x-0 bottom-0 h-12 bg-gradient-to-t from-white to-transparent"></div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>
