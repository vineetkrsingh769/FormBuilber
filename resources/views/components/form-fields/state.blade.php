@props(['label' => 'State', 'required' => false, 'cssClass' => ''])

<div>
    <x-form-fields.label :required="$required">{{ $label }}</x-form-fields.label>
    <x-form-fields.select :options="['California', 'Texas', 'New York', 'Florida']" :class="$cssClass" />
</div>
