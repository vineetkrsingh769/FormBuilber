{{-- Renders a field preview inside Alpine x-for scope (uses `field` variable) --}}

<div class="space-y-1.5">
    <template x-if="field.type === 'text'">
        <div>
            <x-form-fields.label x-bind:required="field.required"><span x-text="field.label"></span></x-form-fields.label>
            <x-form-fields.input type="text" disabled
                x-bind:placeholder="field.placeholder || 'Enter text...'"
                x-bind:value="field.defaultValue"
                x-bind:class="field.cssClass" />
        </div>
    </template>

    <template x-if="field.type === 'textarea'">
        <div>
            <x-form-fields.label x-bind:required="field.required"><span x-text="field.label"></span></x-form-fields.label>
            <x-form-fields.textarea disabled
                x-bind:placeholder="field.placeholder || 'Enter text...'"
                x-bind:class="field.cssClass" />
        </div>
    </template>

    <template x-if="field.type === 'number'">
        <div>
            <x-form-fields.label x-bind:required="field.required"><span x-text="field.label"></span></x-form-fields.label>
            <x-form-fields.input type="number" disabled
                x-bind:placeholder="field.placeholder || '0'"
                x-bind:value="field.defaultValue"
                x-bind:class="field.cssClass" />
        </div>
    </template>

    <template x-if="field.type === 'email'">
        <div>
            <x-form-fields.label x-bind:required="field.required"><span x-text="field.label"></span></x-form-fields.label>
            <x-form-fields.input type="email" disabled
                x-bind:placeholder="field.placeholder || 'name@example.com'"
                x-bind:value="field.defaultValue"
                x-bind:class="field.cssClass" />
        </div>
    </template>

    <template x-if="field.type === 'phone'">
        <div>
            <x-form-fields.label x-bind:required="field.required"><span x-text="field.label"></span></x-form-fields.label>
            <x-form-fields.input type="tel" disabled
                x-bind:placeholder="field.placeholder || '+1 (555) 000-0000'"
                x-bind:class="field.cssClass" />
        </div>
    </template>

    <template x-if="field.type === 'dropdown'">
        <div>
            <x-form-fields.label x-bind:required="field.required"><span x-text="field.label"></span></x-form-fields.label>
            <select disabled
                x-bind:class="'w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm ' + (field.cssClass || '')">
                <option value="">Select an option</option>
                <template x-for="(opt, i) in field.options" :key="i">
                    <option x-text="opt" :value="opt"></option>
                </template>
            </select>
        </div>
    </template>

    <template x-if="field.type === 'radio'">
        <div>
            <x-form-fields.label x-bind:required="field.required"><span x-text="field.label"></span></x-form-fields.label>
            <div class="space-y-2">
                <template x-for="(opt, i) in field.options" :key="i">
                    <label class="flex items-center gap-2 text-sm text-slate-600">
                        <x-form-fields.input type="radio" disabled class="!w-auto text-indigo-600" />
                        <span x-text="opt"></span>
                    </label>
                </template>
            </div>
        </div>
    </template>

    <template x-if="field.type === 'checkbox'">
        <div>
            <x-form-fields.label x-bind:required="field.required"><span x-text="field.label"></span></x-form-fields.label>
            <div class="space-y-2">
                <template x-for="(opt, i) in field.options" :key="i">
                    <label class="flex items-center gap-2 text-sm text-slate-600">
                        <x-form-fields.input type="checkbox" disabled class="!w-auto rounded" />
                        <span x-text="opt"></span>
                    </label>
                </template>
            </div>
        </div>
    </template>

    <template x-if="field.type === 'date'">
        <div>
            <x-form-fields.label x-bind:required="field.required"><span x-text="field.label"></span></x-form-fields.label>
            <x-form-fields.input type="date" disabled x-bind:class="field.cssClass" />
        </div>
    </template>

    <template x-if="field.type === 'file'">
        <div>
            <x-form-fields.label x-bind:required="field.required"><span x-text="field.label"></span></x-form-fields.label>
            <x-form-fields.input type="file" disabled x-bind:class="field.cssClass" />
        </div>
    </template>

    <template x-if="field.type === 'title'">
        <h3 class="text-lg font-semibold text-slate-800" x-text="field.label"></h3>
    </template>

    <template x-if="field.type === 'description'">
        <p class="text-sm leading-relaxed text-slate-500" x-text="field.label"></p>
    </template>

    <template x-if="field.type === 'newline'">
        <div class="h-4 border-b border-dashed border-slate-200"></div>
    </template>

    <template x-if="field.type === 'pagebreak'">
        <div class="flex items-center gap-3 py-2">
            <div class="h-px flex-1 bg-slate-200"></div>
            <span class="text-xs font-medium uppercase tracking-wider text-slate-400">Page Break</span>
            <div class="h-px flex-1 bg-slate-200"></div>
        </div>
    </template>

    <template x-if="field.type === 'hidden'">
        <div class="rounded-lg border border-dashed border-slate-300 bg-slate-50 px-3 py-2 text-xs text-slate-500">
            Hidden field: <span class="font-mono" x-text="field.label"></span>
        </div>
    </template>

    <template x-if="field.type === 'state'">
        <div>
            <x-form-fields.label x-bind:required="field.required"><span x-text="field.label"></span></x-form-fields.label>
            <x-form-fields.select disabled :options="['California', 'Texas', 'New York', 'Florida']"
                x-bind:class="field.cssClass" />
        </div>
    </template>

    <template x-if="field.type === 'city'">
        <div>
            <x-form-fields.label x-bind:required="field.required"><span x-text="field.label"></span></x-form-fields.label>
            <x-form-fields.input type="text" disabled
                x-bind:placeholder="field.placeholder || 'Enter city'"
                x-bind:class="field.cssClass" />
        </div>
    </template>

    <template x-if="field.type === 'state-city'">
        <div class="grid gap-3 sm:grid-cols-2">
            <div>
                <x-form-fields.label x-bind:required="field.required">State</x-form-fields.label>
                <x-form-fields.select disabled :options="['California', 'Texas', 'New York']" />
            </div>
            <div>
                <x-form-fields.label x-bind:required="field.required">City</x-form-fields.label>
                <x-form-fields.input type="text" disabled placeholder="Enter city" />
            </div>
        </div>
    </template>
</div>
