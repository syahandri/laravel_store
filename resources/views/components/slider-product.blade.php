<div class="container py-4">
   <div id="slider-product" class="carousel carousel-dark slide" data-bs-ride="carousel">
      <div class="carousel-indicators">
         @foreach ($products as $product)
            <button type="button" data-bs-target="#slider-product" data-bs-slide-to="{{ $loop->index }}" class="{{ $loop->first ? 'active' : '' }}"></button>
         @endforeach
      </div>
      <div class="carousel-inner rounded-3 overflow-hidden">
         @foreach ($products as $product)
            <a href="/product/detail/{{ $product->slug }}" class="text-decoration-none">
               <div class="carousel-item {{ $loop->first ? 'active' : '' }}" data-bs-interval="3000">
                  <div class="row">
                     <div class="col-12 col-md-6 col-lg-4">
                        <div class="card rounded-3 position-relative overflow-hidden">
                           <img src="{{ asset('storage/img/' . $product->image_1) }}" class="d-block w-100" alt="{{ $product->name }}" loading="lazy">
                           <div class="position-absolute w-100 h-100 start-0 bg-dark top-0 bg-opacity-25"></div>
                        </div>
                     </div>
                     <div class="col-md-6 col-lg-4 d-none d-md-block">
                        <div class="card rounded-3 position-relative overflow-hidden">
                           <img src="{{ asset('storage/img/' . $product->image_2) }}" class="d-block w-100" alt="{{ $product->name }}" loading="lazy">
                           <div class="position-absolute w-100 h-100 start-0 bg-dark top-0 bg-opacity-25"></div>
                        </div>
                     </div>
                     <div class="col-lg-4 d-none d-lg-block">
                        <div class="card rounded-3 position-relative overflow-hidden">
                           <img src="{{ asset('storage/img/' . $product->image_3) }}" class="d-block w-100" alt="{{ $product->name }}" loading="lazy">
                           <div class="position-absolute w-100 h-100 start-0 bg-dark top-0 bg-opacity-25"></div>
                        </div>
                     </div>
                  </div>
                  <div class="carousel-caption d-none d-md-block text-bg-dark rounded-3 bg-opacity-75">
                     <h4 class="text-uppercase">{{ $product->name }}</h4>
                     @if ($product->discount)
                        <p class="fs-5 text-lighter text-decoration-line-through my-0 mt-3 opacity-75"></i> Rp. {{ number_format($product->price, '0', ',', '.') }}</p>
                        <p class="fs-4 fw-semibold text-warning my-0">
                           Rp. {{ number_format($product->price - ($product->price * $product->discount) / 100, '0', ',', '.') }}
                           <i class="fa-solid fa-tag"></i>
                        </p>
                     @else
                        <p class="fs-4 fw-semibold text-warning mt-3 mb-0">Rp. {{ number_format($product->price, '0', ',', '.') }} <i class="fa-solid fa-tag"></i></p>
                     @endif
                  </div>
               </div>
            </a>
         @endforeach
      </div>
      <button class="carousel-control-prev" type="button" data-bs-target="#slider-product" data-bs-slide="prev">
         <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#slider-product" data-bs-slide="next">
         <span class="carousel-control-next-icon" aria-hidden="true"></span>
      </button>
   </div>
</div>
