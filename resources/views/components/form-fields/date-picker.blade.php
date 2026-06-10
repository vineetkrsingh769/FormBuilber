@props(['label' => 'Date Picker', 'required' => false, 'cssClass' => ''])

<div>
    <x-form-fields.label :required="$required">{{ $label }}</x-form-fields.label>
    <x-form-fields.input type="date" :class="$cssClass" />
</div>
