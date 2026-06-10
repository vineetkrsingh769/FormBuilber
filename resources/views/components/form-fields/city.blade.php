@props(['label' => 'City', 'placeholder' => 'Enter city', 'required' => false, 'cssClass' => ''])

<div>
    <x-form-fields.label :required="$required">{{ $label }}</x-form-fields.label>
    <x-form-fields.input type="text" :placeholder="$placeholder" :class="$cssClass" />
</div>
