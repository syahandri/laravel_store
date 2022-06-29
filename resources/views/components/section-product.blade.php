<section {{ $attributes->merge(['class' => 'py-4']) }} id="{{ $id }}">
   <div class="container">
      <h5>Produk {{ $id }}</h5>
      <div class="row justify-content-center">
         @foreach ($products as $product)
            <div class="col-sm-6 col-md-4 col-lg-3 small mb-4">
               <x-card-product discount="{{ $product->discount }}" img="{{ asset('storage/img/' . $product->image_1) }}" alt="{{ $product->name }}"
                  name="{{ $product->name }}" :categories="$product->categories" price="{{ $product->price }}" link="/product/detail/{{ $product->slug }}" />
            </div>
         @endforeach
      </div>
      <div class="d-flex justify-content-center align-items-center my-3">
         <a href="/product?q={{ $id }}" role="button" class="btn btn-danger rounded-4 px-4">Semua Produk {{ $id }}</a>
      </div>
   </div>
</section>