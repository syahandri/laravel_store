<div {{ $attributes->merge(['class' => 'alert alert-' . $type . ' text-center shadow-sm']) }} role="alert">
   {{ $slot }}
</div>
