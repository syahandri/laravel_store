<label class="form-label my-1" for="{{ $id }}">{{ $label }}</label>
<select {{ $attributes->merge(['class' => 'form form-select form-select-sm']) }} name="{{ $id }}" id="{{ $id }}" required>
   {{ $options }}
</select>