<div class="appearance-panel p-4 space-y-5">
    <div>
        <p class="text-[10px] font-bold uppercase tracking-widest text-indigo-500">Form Appearance</p>
        <p class="mt-1 text-xs text-slate-500">Settings apply only to this form. Each saved form keeps its own.</p>
    </div>

    {{-- Header --}}
    <section class="space-y-3 rounded-xl border border-slate-200 bg-slate-50/50 p-3">
        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Header</p>
        <div>
            <x-form-fields.label>Description</x-form-fields.label>
            <x-form-fields.textarea rows="2" x-model="settings.description" @input="onSettingsChange()" placeholder="Shown below the title in preview" />
        </div>
        <label class="flex items-center gap-2.5 rounded-lg border border-slate-200 bg-white px-3 py-2.5 cursor-pointer select-none">
            <x-form-fields.input type="checkbox" x-model="settings.showFormTitle" @change="onSettingsToggle()" class="!w-auto rounded" />
            <span class="text-sm font-medium text-slate-700">Show form title</span>
        </label>
        <label class="flex items-center gap-2.5 rounded-lg border border-slate-200 bg-white px-3 py-2.5 cursor-pointer select-none">
            <x-form-fields.input type="checkbox" x-model="settings.showDescription" @change="onSettingsToggle()" class="!w-auto rounded" />
            <span class="text-sm font-medium text-slate-700">Show description</span>
        </label>
    </section>

    {{-- Theme & layout --}}
    <section class="space-y-3 rounded-xl border border-slate-200 bg-slate-50/50 p-3">
        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Theme & Layout</p>
        <div>
            <x-form-fields.label>Theme color</x-form-fields.label>
            <div class="mt-2 flex flex-wrap gap-2">
                <template x-for="t in themes" :key="t.id">
                    <button type="button" @click="setTheme(t.id)"
                        :class="[t.class, settings.theme === t.id ? 'ring-2 ring-offset-2 ring-indigo-400' : 'ring-0']"
                        class="h-8 w-8 rounded-full bg-gradient-to-br shadow-md transition-shadow duration-150"
                        :title="t.label"></button>
                </template>
            </div>
            <div class="mt-3 rounded-lg border border-slate-200 bg-white p-2.5">
                <div class="flex items-center gap-3">
                    <label class="relative flex h-9 w-9 shrink-0 cursor-pointer overflow-hidden rounded-full shadow-md ring-1 ring-slate-200 transition-shadow"
                        :class="isCustomTheme() ? 'ring-2 ring-indigo-400 ring-offset-2' : ''"
                        :style="currentTheme.swatchStyle || ''">
                        <input type="color"
                            class="absolute inset-0 h-full w-full cursor-pointer border-0 bg-transparent p-0 opacity-0"
                            :value="customThemePickerValue" @input="onCustomColorInput($event)" title="Pick custom color" />
                        <span class="pointer-events-none absolute inset-0 rounded-full" :style="currentTheme.swatchStyle || (isCustomTheme() ? `background: ${settings.theme}` : '')"></span>
                    </label>
                    <div class="min-w-0 flex-1">
                        <p class="text-xs font-semibold text-slate-700">Custom color</p>
                        <p class="truncate text-[10px] font-mono uppercase text-slate-400" x-text="isCustomTheme() ? settings.theme : 'Select a color'"></p>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <x-form-fields.label>Form width</x-form-fields.label>
            <div class="mt-2 grid grid-cols-2 gap-1.5">
                <template x-for="w in widths" :key="w.id">
                    <button type="button" @click="setFormWidth(w.id)"
                        :class="settings.formWidth === w.id ? 'border-indigo-500 bg-indigo-50 text-indigo-700' : 'border-slate-200 bg-white text-slate-600'"
                        class="rounded-lg border py-1.5 text-[10px] font-bold transition-colors duration-150" x-text="w.label"></button>
                </template>
                <button type="button" @click="setFormWidth('custom')"
                    :class="settings.formWidth === 'custom' ? 'border-indigo-500 bg-indigo-50 text-indigo-700' : 'border-slate-200 bg-white text-slate-600'"
                    class="col-span-2 rounded-lg border py-1.5 text-[10px] font-bold transition-colors duration-150">
                    Custom width
                </button>
            </div>
            <div class="appearance-field-slot mt-2" :class="settings.formWidth === 'custom' ? '' : 'appearance-field-slot--hidden'">
                <x-form-fields.label>Width in pixels (320–1600)</x-form-fields.label>
                <div class="flex items-center gap-2">
                    <x-form-fields.input type="number" min="320" max="1600" step="10"
                        x-model.number="settings.customFormWidth" @input="onCustomWidthInput()" class="flex-1" />
                    <span class="text-xs font-medium text-slate-400">px</span>
                </div>
            </div>
        </div>
        <div>
            <x-form-fields.label>Grid columns</x-form-fields.label>
            <div class="mt-2 grid grid-cols-3 gap-1.5">
                <template x-for="t in gridTemplates" :key="t.cols">
                    <button type="button" @click="setGridColumns(t.cols)"
                        :class="settings.gridColumns === t.cols ? 'border-indigo-500 bg-indigo-50 text-indigo-700' : 'border-slate-200 bg-white text-slate-600'"
                        class="rounded-lg border py-2 text-[10px] font-bold transition-colors duration-150" x-text="`${t.cols} Col`"></button>
                </template>
            </div>
        </div>
    </section>

    {{-- Action buttons --}}
    <section class="space-y-3 rounded-xl border border-slate-200 bg-slate-50/50 p-3">
        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Action Buttons</p>
        <label class="flex items-center gap-2.5 rounded-lg border border-slate-200 bg-white px-3 py-2.5 cursor-pointer select-none">
            <x-form-fields.input type="checkbox" x-model="settings.showSubmitButton" @change="onSettingsToggle()" class="!w-auto rounded" />
            <span class="text-sm font-medium text-slate-700">Show submit button</span>
        </label>
        <div class="appearance-field-slot" :class="settings.showSubmitButton ? '' : 'appearance-field-slot--hidden'">
            <x-form-fields.label>Submit label</x-form-fields.label>
            <x-form-fields.input type="text" x-model="settings.submitLabel" @input="onSettingsChange()" />
        </div>
        <label class="flex items-center gap-2.5 rounded-lg border border-slate-200 bg-white px-3 py-2.5 cursor-pointer select-none">
            <x-form-fields.input type="checkbox" x-model="settings.showResetButton" @change="onSettingsToggle()" class="!w-auto rounded" />
            <span class="text-sm font-medium text-slate-700">Show clear / reset button</span>
        </label>
        <div class="appearance-field-slot" :class="settings.showResetButton ? '' : 'appearance-field-slot--hidden'">
            <x-form-fields.label>Clear button label</x-form-fields.label>
            <x-form-fields.input type="text" x-model="settings.resetLabel" @input="onSettingsChange()" />
        </div>
    </section>

    {{-- After submit --}}
    <section class="space-y-3 rounded-xl border border-slate-200 bg-slate-50/50 p-3">
        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">After Submit</p>
        <label class="flex items-center gap-2.5 rounded-lg border border-slate-200 bg-white px-3 py-2.5 cursor-pointer select-none">
            <x-form-fields.input type="checkbox" x-model="settings.showSuccessMessage" @change="onSettingsToggle()" class="!w-auto rounded" />
            <span class="text-sm font-medium text-slate-700">Show success message</span>
        </label>
        <div class="appearance-field-slot" :class="settings.showSuccessMessage ? '' : 'appearance-field-slot--hidden'">
            <x-form-fields.label>Success message</x-form-fields.label>
            <x-form-fields.input type="text" x-model="settings.successMessage" @input="onSettingsChange()" />
        </div>
    </section>

    <div class="grid grid-cols-2 gap-2">
        <button type="button" @click="resetAppearance()"
            class="flex items-center justify-center gap-1.5 rounded-xl border border-slate-200 py-2.5 text-xs font-semibold text-slate-600 hover:bg-slate-50">
            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            Reset
        </button>
        <button type="button" @click="exportJson()"
            class="rounded-xl border border-slate-200 py-2.5 text-xs font-semibold text-slate-600 hover:bg-slate-50">
            Export JSON
        </button>
    </div>
</div>
