@props([
    'label',
    'name',
    'options' => [],
    'selected' => '',
])

<div class="mb-3">
    <label for="{{ $name }}" class="form-label">{{ $label }}</label>
    <select 
        id="{{ $name }}" 
        name="{{ $name }}" 
        {{ $attributes->merge(['class' => 'form-control']) }}
    >
        {{--  <!-- Conditionally set placeholder text based on whether in edit mode -->  --}}
        <option value="">
            {{ $selected ? ucfirst($options[$selected]) : "-- Select {$label} --" }}
        </option>
        @foreach ($options as $value => $text)
            <option value="{{ $value }}" 
                {{ (old($name) ?: $selected) == $value ? 'selected' : '' }}>
                {{ ucfirst($text) }}
            </option>
        @endforeach
    </select>
    @error($name)
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>
