@props(['label' => 'Text Area', 'placeholder' => '', 'required' => false, 'cssClass' => ''])

<div>
    <x-form-fields.label :required="$required">{{ $label }}</x-form-fields.label>
    <x-form-fields.textarea :placeholder="$placeholder" :class="$cssClass" />
</div>
