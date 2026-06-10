@props(['label' => 'Dropdown', 'options' => [], 'required' => false, 'cssClass' => ''])

<div>
    <x-form-fields.label :required="$required">{{ $label }}</x-form-fields.label>
    <x-form-fields.select :options="$options" :class="$cssClass" />
</div>
