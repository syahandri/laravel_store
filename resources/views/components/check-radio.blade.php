<input {{ $attributes->merge(['class' => 'form form-check-input', 'type' => $type]) }} id="{{ $id }}" name="{{ $name }}" value="{{ $value }}">
<label class="form-check-label" for="{{ $id }}">{{ $label }}</label>
