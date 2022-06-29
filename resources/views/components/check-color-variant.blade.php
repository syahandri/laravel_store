<input class="form-check-input d-none" type="{{ $type }}" name="color" id="color-{{ $color }}" value="{{ $color }}" {{ $checked == 0 ? 'checked' : '' }}
   required>
<label for="color-{{ $color }}">
   <div class="color small position-relative text-capitalize rounded px-2 shadow-sm">
      {{ $color }} <i class="fa-solid fa-circle text-{{ $color }}"></i>
   </div>
</label>
