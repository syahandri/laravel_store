@extends('guest.layout.main')
@section('content')
   <!-- Slider produk -->
   <x-slider-product :products="$slider" />

   <!-- Section produk terbaru -->
   <x-section-product id="terbaru" :products="$latest" />

   <!-- Section produk terlaris-->
   <x-section-product id="terlaris" class="bg-light" :products="$best_seller" />

   <!-- Section produk termurah -->
   <x-section-product id="termurah" :products="$cheap" />
@endsection
