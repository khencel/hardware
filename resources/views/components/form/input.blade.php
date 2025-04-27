@props([
    'label',
    'type' => 'text',
    'name',
    'value' => '',
])

<div class="mb-3">
    <label for="{{ $name }}" class="form-label">{{ $label }}</label>
    <input 
        type="{{ $type }}" 
        id="{{ $name }}" 
        name="{{ $name }}" 
        {{ $attributes->merge(['class' => 'form-control']) }} 
        value="{{ old($name, $value) }}"
    >
    @error($name)
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>
