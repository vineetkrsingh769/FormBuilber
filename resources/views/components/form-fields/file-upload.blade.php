@props(['label' => 'File Upload', 'required' => false, 'cssClass' => ''])

<div>
    <x-form-fields.label :required="$required">{{ $label }}</x-form-fields.label>
    <x-form-fields.input type="file" :class="$cssClass" />
</div>
