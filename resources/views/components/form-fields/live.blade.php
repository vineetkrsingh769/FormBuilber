{{-- Interactive live form field — used in preview mode with previewValues --}}

<div>
    <template x-if="field.type === 'text'">
        <div>
            <x-form-fields.label x-bind:required="field.required"><span x-text="field.label"></span></x-form-fields.label>
            <x-form-fields.input type="text"
                x-model="previewValues[field.id]"
                x-bind:placeholder="field.placeholder || 'Enter text...'"
                x-bind:minlength="field.minChars"
                x-bind:maxlength="field.maxChars"
                x-bind:required="field.required"
                x-bind:class="field.cssClass" />
        </div>
    </template>

    <template x-if="field.type === 'textarea'">
        <div>
            <x-form-fields.label x-bind:required="field.required"><span x-text="field.label"></span></x-form-fields.label>
            <x-form-fields.textarea
                x-model="previewValues[field.id]"
                x-bind:placeholder="field.placeholder || 'Enter text...'"
                x-bind:minlength="field.minChars"
                x-bind:maxlength="field.maxChars"
                x-bind:required="field.required"
                x-bind:class="field.cssClass" />
        </div>
    </template>

    <template x-if="field.type === 'number'">
        <div>
            <x-form-fields.label x-bind:required="field.required"><span x-text="field.label"></span></x-form-fields.label>
            <x-form-fields.input type="number"
                x-model="previewValues[field.id]"
                x-bind:placeholder="field.placeholder || '0'"
                x-bind:required="field.required"
                x-bind:class="field.cssClass" />
        </div>
    </template>

    <template x-if="field.type === 'email'">
        <div>
            <x-form-fields.label x-bind:required="field.required"><span x-text="field.label"></span></x-form-fields.label>
            <x-form-fields.input type="email"
                x-model="previewValues[field.id]"
                x-bind:placeholder="field.placeholder || 'name@example.com'"
                x-bind:required="field.required"
                x-bind:class="field.cssClass" />
        </div>
    </template>

    <template x-if="field.type === 'phone'">
        <div>
            <x-form-fields.label x-bind:required="field.required"><span x-text="field.label"></span></x-form-fields.label>
            <x-form-fields.input type="tel"
                x-model="previewValues[field.id]"
                x-bind:placeholder="field.placeholder || '+1 (555) 000-0000'"
                x-bind:required="field.required"
                x-bind:class="field.cssClass" />
        </div>
    </template>

    <template x-if="field.type === 'dropdown'">
        <div>
            <x-form-fields.label x-bind:required="field.required"><span x-text="field.label"></span></x-form-fields.label>
            <select x-model="previewValues[field.id]"
                x-bind:required="field.required"
                x-bind:class="'w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm shadow-sm transition focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-100 ' + (field.cssClass || '')">
                <option value="">Select an option</option>
                <template x-for="(opt, i) in field.options" :key="i">
                    <option :value="opt" x-text="opt"></option>
                </template>
            </select>
        </div>
    </template>

    <template x-if="field.type === 'radio'">
        <div>
            <x-form-fields.label x-bind:required="field.required"><span x-text="field.label"></span></x-form-fields.label>
            <div class="space-y-2.5">
                <template x-for="(opt, i) in field.options" :key="i">
                    <label class="flex cursor-pointer items-center gap-3 rounded-lg border border-slate-100 px-3 py-2 transition hover:bg-slate-50">
                        <input type="radio" :name="'radio_' + field.id" :value="opt"
                            x-model="previewValues[field.id]"
                            x-bind:required="field.required && i === 0"
                            class="text-indigo-600 focus:ring-indigo-500" />
                        <span class="text-sm text-slate-700" x-text="opt"></span>
                    </label>
                </template>
            </div>
        </div>
    </template>

    <template x-if="field.type === 'checkbox'">
        <div>
            <x-form-fields.label x-bind:required="field.required"><span x-text="field.label"></span></x-form-fields.label>
            <div class="space-y-2.5">
                <template x-for="(opt, i) in field.options" :key="i">
                    <label class="flex cursor-pointer items-center gap-3 rounded-lg border border-slate-100 px-3 py-2 transition hover:bg-slate-50">
                        <input type="checkbox" :value="opt"
                            :checked="isCheckboxChecked(field.id, opt)"
                            @change="toggleCheckbox(field.id, opt)"
                            class="rounded text-indigo-600 focus:ring-indigo-500" />
                        <span class="text-sm text-slate-700" x-text="opt"></span>
                    </label>
                </template>
            </div>
        </div>
    </template>

    <template x-if="field.type === 'date'">
        <div>
            <x-form-fields.label x-bind:required="field.required"><span x-text="field.label"></span></x-form-fields.label>
            <x-form-fields.input type="date"
                x-model="previewValues[field.id]"
                x-bind:required="field.required"
                x-bind:class="field.cssClass" />
        </div>
    </template>

    <template x-if="field.type === 'file'">
        <div>
            <x-form-fields.label x-bind:required="field.required"><span x-text="field.label"></span></x-form-fields.label>
            <x-form-fields.input type="file"
                @change="previewValues[field.id] = $event.target.files[0]?.name || ''"
                x-bind:required="field.required"
                x-bind:class="field.cssClass" />
            <p x-show="previewValues[field.id]" class="mt-1.5 text-xs text-slate-500">
                Selected: <span x-text="previewValues[field.id]"></span>
            </p>
        </div>
    </template>

    <template x-if="field.type === 'title'">
        <h3 class="text-xl font-bold text-slate-900" x-text="field.label"></h3>
    </template>

    <template x-if="field.type === 'description'">
        <p class="text-sm leading-relaxed text-slate-500" x-text="field.label"></p>
    </template>

    <template x-if="field.type === 'newline'">
        <div class="h-6"></div>
    </template>

    <template x-if="field.type === 'pagebreak'">
        <div class="flex items-center gap-4 py-4">
            <div class="h-px flex-1 bg-gradient-to-r from-transparent via-slate-200 to-transparent"></div>
            <span class="text-[10px] font-bold uppercase tracking-widest text-slate-300">Page Break</span>
            <div class="h-px flex-1 bg-gradient-to-r from-transparent via-slate-200 to-transparent"></div>
        </div>
    </template>

    <template x-if="field.type === 'state'">
        <div>
            <x-form-fields.label x-bind:required="field.required"><span x-text="field.label"></span></x-form-fields.label>
            <select x-model="previewValues[field.id]"
                x-bind:required="field.required"
                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm shadow-sm transition focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-100">
                <option value="">Select state</option>
                <option value="California">California</option>
                <option value="Texas">Texas</option>
                <option value="New York">New York</option>
                <option value="Florida">Florida</option>
            </select>
        </div>
    </template>

    <template x-if="field.type === 'city'">
        <div>
            <x-form-fields.label x-bind:required="field.required"><span x-text="field.label"></span></x-form-fields.label>
            <x-form-fields.input type="text"
                x-model="previewValues[field.id]"
                x-bind:placeholder="field.placeholder || 'Enter city'"
                x-bind:required="field.required"
                x-bind:class="field.cssClass" />
        </div>
    </template>

    <template x-if="field.type === 'state-city'">
        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <x-form-fields.label x-bind:required="field.required">State</x-form-fields.label>
                <select x-model="previewValues[field.id + '_state']"
                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm shadow-sm">
                    <option value="">Select state</option>
                    <option value="California">California</option>
                    <option value="Texas">Texas</option>
                    <option value="New York">New York</option>
                </select>
            </div>
            <div>
                <x-form-fields.label x-bind:required="field.required">City</x-form-fields.label>
                <x-form-fields.input type="text"
                    x-model="previewValues[field.id + '_city']"
                    placeholder="Enter city" />
            </div>
        </div>
    </template>
</div>
