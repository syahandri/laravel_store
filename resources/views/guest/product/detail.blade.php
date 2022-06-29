@extends('guest.layout.main')
@section('content')
   <div class="container p-4">
      <div class="row">
         <div class="col-12 col-md-6">
            <div class="card overflow-hidden rounded">
               <div class="position-relative">
                  @if ($product->discount)
                     <div class="position-absolute text-bg-success discount start-0 small py-2 px-3 shadow">Diskon {{ $product->discount }}%</div>
                  @endif
                  <img src="{{ asset('storage/img/' . $product->image_1) }}" alt="{{ $product->name }}" class="img-fluid main-img w-100 h-100" loading="lazy" />
                  <div class="position-absolute bg-dark w-100 h-100 start-0 top-0 bg-opacity-25"></div>
               </div>
            </div>
            <div class="row mt-4">
               <div class="col-4">
                  <div class="card overflow-hidden rounded">
                     <img src="{{ asset('storage/img/' . $product->image_1) }}" alt="{{ $product->name }}" class="img-fluid other-img" loading="lazy" />
                  </div>
               </div>
               <div class="col-4">
                  <div class="card overflow-hidden rounded">
                     <img src="{{ asset('storage/img/' . $product->image_2) }}" alt="{{ $product->name }}" class="img-fluid other-img" loading="lazy" />
                  </div>
               </div>
               <div class="col-4">
                  <div class="card overflow-hidden rounded">
                     <img src="{{ asset('storage/img/' . $product->image_3) }}" alt="{{ $product->name }}" class="img-fluid other-img" loading="lazy" />
                  </div>
               </div>
            </div>
         </div>
         <hr class="d-block d-md-none my-4">
         <div class="col-12 col-md-6 py-lg-5 small py-0">
            @if (!$product->stock)
               <x-alert type="danger"><span class="fs-6">Stok Sudah Habis!</span></x-alert>
            @endif
            <form action="/cart/add" method="post" id="form-add-cart">
               @csrf
               <input type="hidden" name="product_id" value="{{ $product->id }}">
               <h4 class="text-capitalize">{{ $product->name }}</h4>
               @foreach ($product->categories as $category)
                  <span class="badge text-dark bg-secondary border-secondary border-1 rounded-pill fw-normal me-1 my-1 border bg-opacity-25">
                     {{ $category->name }}
                  </span>
               @endforeach

               <div class="d-flex align-items-center mt-3 flex-wrap">
                  @if ($product->discount)
                     <span class="text-secondary text-decoration-line-through me-2"></i> Rp. {{ number_format($product->price, '0', ',', '.') }}</span>
                     <span class="fs-4 fw-semibold text-danger">Rp. {{ number_format($product->price - ($product->price * $product->discount) / 100, '0', ',', '.') }} <i
                           class="fa-solid fa-tag"></i></span>
                  @else
                     <span class="fs-4 fw-semibold text-danger">Rp. {{ number_format($product->price, '0', ',', '.') }} <i class="fa-solid fa-tag"></i></span>
                  @endif
               </div>
               <p class="mt-2 mb-1">Varian Warna</p>
               <div class="d-flex align-items-center flex-wrap">
                  @foreach ($product->colors as $color)
                     <div class="me-2 mb-2">
                        <x-check-color-variant type="radio" color="{{ $color->name }}" checked="{{ $loop->index }}" />
                     </div>
                  @endforeach
               </div>
               <x-select class="w-25" id="size" label="Ukuran">
                  <x-slot:options>
                     @foreach ($product->sizes as $size)
                        <option value="{{ $size->name }}">{{ $size->name }}</option>
                     @endforeach
                  </x-slot:options>
               </x-select>
               <input type="hidden" name="stock" value="{{ $product->stock }}">
               <x-input type="number" class="w-25" id="quantity" value="1" min="1" max="{{ $product->stock }}" label="Jumlah" />
               <div class="invalid-feedback invalid-quantity"></div>

               <div class="d-flex align-items-center small my-3 flex-wrap">
                  <span class="me-3 text-danger"><i class="fa-solid fa-circle-check"></i></i> Ready</span>
                  <span class="me-3 text-danger"><i class="fa-solid fa-certificate"></i> Original</span>
                  <span class="me-3 text-danger"><i class="fa-solid fa-calendar-check"></i></i> Garansi</span>
               </div>

               @auth
                  <x-button type="submit" class="{{ $product->stock ? 'btn-success' : 'btn-secondary disabled' }} px-3"><i class="fa-solid fa-cart-shopping"></i> Tambahkan kekeranjang</x-button>
               @else
                  <a href="{{ route('login') }}" class="btn btn-sm btn-danger px-3"><i class="fa-solid fa-sign-in"></i> Login</a>
               @endauth
            </form>
         </div>
         <hr class="my-4">
      </div>
      <div class="row">
         <div class="col-12 col-md-5">
            <h6>Informasi Produk</h6>
            <div class="row">
               <div class="col-5">
                  <ul class="list-unstyled small">
                     <li class="mb-2">Berat</li>
                     <li class="mb-2">Kondisi</li>
                     <li class="mb-2">Pemesanan Min.</li>
                     <li class="mb-2">Kategori</li>
                  </ul>
               </div>
               <div class="col-7">
                  <ul class="list-unstyled small">
                     <li class="mb-2">{{ number_format($product->weight) }} Gram</li>
                     <li class="mb-2">Baru</li>
                     <li class="mb-2">1 Buah</li>
                     <li class="mb-2">
                        @foreach ($product->categories as $category)
                           <a href="/product/category/{{ $category->slug }}" class="text-decoration-none link-success">{{ $category->name }} {{ $loop->last ? '' : ', ' }}</a>
                        @endforeach
                     </li>
                  </ul>
               </div>
            </div>
         </div>
         <div class="col-12 col-md-7">
            <h6>Deskripsi Produk</h6>
            <p class="small my-0">
               {!! $product->detail !!}
            </p>
         </div>
      </div>
      <hr class="my-4">
      <div class="row justify-content-center mt-5">
         <h5>Produk Terkait</h5>
         @foreach ($related_products as $product)
            <div class="col-sm-6 col-md-4 col-lg-3 small mb-4">
               <x-card-product discount="{{ $product->discount }}" img="{{ asset('storage/img/' . $product->image_1) }}" alt="{{ $product->name }}"
                  name="{{ $product->name }}" :categories="$product->categories" price="{{ $product->price }}" link="/product/detail/{{ $product->slug }}" />
            </div>
         @endforeach
      </div>
   </div>
@endsection
@push('script')
   <script type="module" src="{{ asset('js/product-detail.js') }}"></script>
@endpush
