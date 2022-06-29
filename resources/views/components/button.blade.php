<button type="{{ $type }}" {{ $attributes->merge(['class' => 'btn btn-sm']) }}>
   {{ $slot }}
</button>
