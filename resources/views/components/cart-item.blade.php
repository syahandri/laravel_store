<div class="card rounded-3 small cart-item mb-2 border-0 p-2 shadow">

   <input type="hidden" name="product_id" value="{{ $id }}" disabled>
   <input type="hidden" name="color" value="{{ $color }}" disabled>
   <input type="hidden" name="size" value="{{ $size }}" disabled>
   <input type="hidden" name="stock" value="{{ $stock }}" disabled>
   <input type="hidden" name="weight" value="{{ $weight }}" disabled>
   <input type="hidden" name="price" value="{{ round($price - ($price * $discount) / 100) }}" disabled>

   <div class="d-flex justify-content-between">
      <div class="form-check form-switch">
         <x-check-radio type="checkbox" class="select-item" role="switch" id="" name="select-item" value="" label="Pilih Item" />
      </div>
      <div>
         <x-button type="button" class="remove-item border-0 bg-transparent"><i class="fa-solid fa-trash text-secondary"></i></x-button>
      </div>
   </div>
   <div class="stock-alert" data-id="{{ $id }}">
      @if (!$stock)
         <x-alert type="danger">Stok Sudah Habis!</x-alert>
      @endif
   </div>
   <div class="row align-items-center justify-content-between">
      <div class="col-12 col-sm-3">
         <div class="position-relative overflow-hidden rounded">
            <img src="{{ $img }}" alt="{{ $alt }}" loading="lazy" class="img-fluid">
            <div class="position-absolute w-100 h-100 start-0 bg-dark top-0 bg-opacity-25"></div>
         </div>
      </div>
      <div class="col-12 col-sm-5 align-self-end">
         <p class="text-capitalize fw-semibold my-0">{{ $name }}</p>
         <p class="text-capitalize my-0">{{ $color }}</p>
         <p class="text-uppercase my-0">{{ $size }}</p>
         <x-input type="text" id="note" placeholder="Mis: Warna cadangan" label="Catatan (Opsional)" />
      </div>
      <div class="col-12 col-sm-4 align-self-end text-start text-sm-end">
         @if ($discount)
            <span class="text-secondary text-decoration-line-through my-2">Rp. {{ number_format($price, '0', ',', '.') }}</span>
            <span class="fw-bold text-danger my-2">Rp. {{ number_format($price - ($price * $discount) / 100, '0', ',', '.') }}</span>
         @else
            <p class="fw-bold text-danger my-2">Rp. {{ number_format($price, '0', ',', '.') }}</p>
         @endif
         <x-input type="number" id="quantity" class="d-inline text-center" min="1" max="{{ $stock }}" value="{{ $quantity > $stock ? $stock : $quantity }}"
            label="Jumlah" />
      </div>
   </div>
</div>
