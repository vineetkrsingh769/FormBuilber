@props(['label' => 'Radio Buttons', 'options' => [], 'required' => false])

<div>
    <x-form-fields.label :required="$required">{{ $label }}</x-form-fields.label>
    <div class="space-y-2">
        @foreach($options as $option)
            <label class="flex items-center gap-2 text-sm text-slate-600">
                <x-form-fields.input type="radio" class="!w-auto" />
                {{ $option }}
            </label>
        @endforeach
    </div>
</div>
