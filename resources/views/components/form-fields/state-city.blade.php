@props(['required' => false])

<div class="grid gap-3 sm:grid-cols-2">
    <x-form-fields.state :required="$required" />
    <x-form-fields.city :required="$required" />
</div>
