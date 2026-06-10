<aside x-show="settings.templateSelected" class="flex min-h-0 w-full shrink-0 flex-col overflow-hidden border-t border-slate-200 bg-white lg:w-[320px] lg:border-l lg:border-t-0">
    <div class="flex shrink-0 border-b border-slate-100">
        <button @click="paletteTab = 'add'" :class="paletteTab === 'add' ? 'border-indigo-600 text-indigo-600 bg-indigo-50/50' : 'text-slate-500'"
            class="flex flex-1 items-center justify-center gap-1.5 border-b-2 py-2.5 text-[11px] font-bold transition-colors">
            @include('form-builder.partials.icon', ['name' => 'add-fields', 'class' => 'h-3.5 w-3.5'])
            <span>Add Fields</span>
        </button>
        <button @click="paletteTab = 'options'" :class="paletteTab === 'options' ? 'border-indigo-600 text-indigo-600 bg-indigo-50/50' : 'text-slate-500'"
            class="flex flex-1 items-center justify-center gap-1.5 border-b-2 py-2.5 text-[11px] font-bold transition-colors">
            @include('form-builder.partials.icon', ['name' => 'edit-field', 'class' => 'h-3.5 w-3.5'])
            <span>Edit Field</span>
        </button>
        <button @click="paletteTab = 'appearance'" :class="paletteTab === 'appearance' ? 'border-indigo-600 text-indigo-600 bg-indigo-50/50' : 'text-slate-500'"
            class="flex flex-1 items-center justify-center gap-1.5 border-b-2 py-2.5 text-[11px] font-bold transition-colors">
            @include('form-builder.partials.icon', ['name' => 'appearance', 'class' => 'h-3.5 w-3.5'])
            <span>Appearance</span>
        </button>
    </div>
    <div class="relative min-h-0 flex-1">
        <div x-show="paletteTab === 'add'" class="absolute inset-0 overflow-y-auto bg-slate-50/60 p-3 space-y-5">
            <template x-for="category in fieldCategories" :key="category.name">
                <div>
                    <div class="mb-2.5 flex items-center gap-2">
                        <span class="h-1 w-1 rounded-full bg-violet-400"></span>
                        <p class="text-[9px] font-bold uppercase tracking-widest text-violet-500" x-text="category.name"></p>
                        <span class="h-px flex-1 bg-gradient-to-r from-violet-200 to-transparent"></span>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <template x-for="ft in category.fields" :key="ft.type">
                            <div draggable="true" @dragstart="onPaletteDragStart($event, ft.type)" @dragend="onPaletteDragEnd()" @click="addField(ft.type)"
                                class="group relative flex cursor-pointer items-center gap-2.5 overflow-hidden rounded-xl border border-slate-200 bg-white px-2.5 py-2.5 text-left shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:border-violet-300 hover:shadow-md hover:shadow-violet-200/60 active:cursor-grabbing active:translate-y-0">
                                <span class="pointer-events-none absolute inset-y-0 left-0 w-1 origin-top scale-y-0 bg-violet-600 transition-transform duration-200 group-hover:scale-y-100"></span>
                                <span class="pointer-events-none flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-violet-100 text-lg leading-none text-violet-700 transition-colors duration-200 group-hover:bg-violet-700 group-hover:text-white" x-html="ft.icon"></span>
                                <span class="pointer-events-none min-w-0 flex-1 truncate text-[11px] font-semibold text-slate-700 transition-colors duration-200 group-hover:text-violet-700" x-text="ft.label"></span>
                            </div>
                        </template>
                    </div>
                </div>
            </template>
        </div>
        <div x-show="paletteTab === 'options'" class="absolute inset-0 overflow-y-auto p-4">
            <div x-show="!selectedField" class="py-16 text-center">
                <p class="text-sm font-medium text-slate-500">Click a field on the canvas</p>
                <p class="mt-1 text-xs text-slate-400">to edit its label, options & rules</p>
            </div>
            <div x-show="selectedField" class="space-y-3">
                <div class="rounded-xl bg-indigo-50 px-3 py-2 text-xs font-bold text-indigo-700" x-text="getFieldMeta(selectedField?.type).label"></div>
                <div><x-form-fields.label>Label</x-form-fields.label><x-form-fields.input type="text" x-model="selectedField.label" @input="onFieldChangeDebounced()" /></div>
                <div x-show="selectedField && hasPlaceholder(selectedField.type)"><x-form-fields.label>Placeholder</x-form-fields.label><x-form-fields.input type="text" x-model="selectedField.placeholder" @input="onFieldChangeDebounced()" /></div>
                <div x-show="selectedField && hasOptions(selectedField.type)">
                    <x-form-fields.label>Options</x-form-fields.label>
                    <template x-for="(opt, i) in selectedField.options" :key="i">
                        <div class="mb-1.5 flex gap-1"><x-form-fields.input type="text" x-model="selectedField.options[i]" @input="onFieldChangeDebounced()" /><button @click="removeOption(i)" class="rounded-lg border px-2 text-slate-400">✕</button></div>
                    </template>
                    <button @click="addOption()" class="w-full rounded-lg border border-dashed py-1.5 text-xs font-semibold text-slate-500">+ Add</button>
                </div>
                <div x-show="selectedField && canSetColSpan(selectedField.type)">
                    <x-form-fields.label>Width in grid</x-form-fields.label>
                    <div class="flex gap-1">
                        <template x-for="n in colSpanOptions" :key="n">
                            <button @click="selectedField.colSpan = n; onColSpanChange()" :class="selectedField.colSpan === n ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-600'" class="flex-1 rounded-lg py-1.5 text-xs font-bold" x-text="n === settings.gridColumns ? 'Full' : n"></button>
                        </template>
                    </div>
                </div>
                <label class="flex items-center gap-2 rounded-xl border px-3 py-2.5 cursor-pointer"><x-form-fields.input type="checkbox" x-model="selectedField.required" @change="onFieldChangeDebounced()" class="!w-auto rounded" /><span class="text-sm font-medium">Required</span></label>
                <button @click="removeFieldFromOptions()" class="flex w-full items-center justify-center gap-2 rounded-xl bg-rose-50 py-2 text-sm font-semibold text-rose-600 hover:bg-rose-100">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    Remove Field
                </button>
            </div>
        </div>
        <div x-show="paletteTab === 'appearance'" class="absolute inset-0 overflow-y-auto">
            @include('form-builder.editor.appearance')
        </div>
    </div>
</aside>
