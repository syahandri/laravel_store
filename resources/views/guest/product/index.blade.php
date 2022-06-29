@extends('guest.layout.main')
@section('content')
   <div class="container py-4">
      <h5 class="text-capitalize mb-4 text-center">{{ $title . request()->q }}</h5>
      <div class="row justify-content-center">
         @forelse ($products as $product)
            <div class="col-sm-6 col-md-4 col-lg-3 small mb-4">
               <x-card-product discount="{{ $product->discount }}" img="{{ asset('storage/img/' . $product->image_1) }}" alt="{{ $product->name }}" name="{{ $product->name }}"
                  :categories="$product->categories" price="{{ $product->price }}" link="/product/detail/{{ $product->slug }}" />
            </div>
         @empty
            <x-alert type="danger">
               <span class="fs-6"><i class="fa-solid fa-triangle-exclamation"></i> Maaf, Saat Ini Produk Belum Ada</span>
            </x-alert>
         @endforelse
      </div>
      {{ $products->links() }}
   </div>
@endsection
