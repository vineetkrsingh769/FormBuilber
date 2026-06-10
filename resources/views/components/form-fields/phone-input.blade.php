@props(['label' => 'Phone Input', 'placeholder' => '', 'required' => false, 'cssClass' => ''])

<div>
    <x-form-fields.label :required="$required">{{ $label }}</x-form-fields.label>
    <x-form-fields.input type="tel" :placeholder="$placeholder" :class="$cssClass" />
</div>
