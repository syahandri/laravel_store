<div class="card card-product rounded-3 h-100 bg-light overflow-hidden border-0 shadow-sm">
   <a href="{{ $link }}" class="text-decoration-none stretched-link">
      <div class="position-relative">
         @if ($discount)
            <div class="position-absolute text-bg-success discount start-0 small py-2 px-3 shadow">
               Diskon {{ $discount }}%
            </div>
         @endif
         <img src="{{ $img }}" class="card-img-top img-fluid" alt="{{ $alt }}" loading="lazy">
         <div class="position-absolute bg-dark w-100 h-100 start-0 top-0 bg-opacity-25"></div>
      </div>
      <div class="card-body text-center">
         <h6 class="card-title text-capitalize fw-semibold text-dark">{{ $name }}</h6>

         @foreach ($categories as $category)
            <span class="badge text-dark bg-secondary border-secondary border-1 rounded-pill fw-normal my-1 border bg-opacity-25">
               {{ $category->name }}
            </span>
         @endforeach

         @if ($discount)
            <p class="card-text text-secondary text-decoration-line-through my-0 mt-3"></i> Rp. {{ number_format($price, '0', ',', '.') }}</p>
            <p class="card-text fw-semibold text-danger fs-5 my-0">Rp. {{ number_format($price - ($price * $discount) / 100, '0', ',', '.') }} <i class="fa-solid fa-tag"></i></p>
         @else
            <p class="card-text fw-semibold text-danger fs-5 mt-3 mb-0">Rp. {{ number_format($price, '0', ',', '.') }} <i class="fa-solid fa-tag"></i></p>
         @endif
      </div>
   </a>
</div>
