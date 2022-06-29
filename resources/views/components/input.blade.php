<label class="form-label d-block my-1" for="{{ $id }}">{{ $label }}</label>
<input type="{{ $type }}" name="{{ $id }}" id="{{ $id }}"
   @error($id) 
      {{ $attributes->merge(['class' => 'is-invalid form form-control form-control-sm mb-1', 'value' => '', 'placeholder' => '', 'min' => '', 'max' => '', 'maxlength' => '']) }}
   @else
      {{ $attributes->merge(['class' => 'form form-control form-control-sm mb-1', 'value' => '', 'placeholder' => '', 'min' => '', 'max' => '', 'maxlength' => '']) }} 
   @enderror 
/>
