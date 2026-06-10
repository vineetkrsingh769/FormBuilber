@props(['label' => 'Email Input', 'placeholder' => '', 'required' => false, 'cssClass' => '', 'value' => ''])

<div>
    <x-form-fields.label :required="$required">{{ $label }}</x-form-fields.label>
    <x-form-fields.input type="email" :placeholder="$placeholder" :value="$value" :class="$cssClass" />
</div>
