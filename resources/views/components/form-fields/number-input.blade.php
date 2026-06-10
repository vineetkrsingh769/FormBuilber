@props(['label' => 'Number Input', 'placeholder' => '', 'required' => false, 'cssClass' => '', 'value' => ''])

<div>
    <x-form-fields.label :required="$required">{{ $label }}</x-form-fields.label>
    <x-form-fields.input type="number" :placeholder="$placeholder" :value="$value" :class="$cssClass" />
</div>
