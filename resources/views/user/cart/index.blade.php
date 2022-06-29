@extends('guest.layout.main')
@section('content')
   <div class="container py-4">
      <h5 class="mb-3">{{ $title }}</h5>
      <form action="" id="form-cart" class="row">
         @if ($carts)
            @csrf
            <div class="col-12 col-md-8">
               @foreach ($carts->products as $product)
                  <x-cart-item id="{{ $product->id }}" name="{{ $product->name }}" stock="{{ $product->stock }}" weight="{{ $product->weight }}" price="{{ $product->price }}"
                     discount="{{ $product->discount }}" quantity="{{ $product->pivot->quantity }}" color="{{ $product->pivot->color }}" size="{{ $product->pivot->size }}"
                     img="{{ asset('storage/img/' . $product->image_1) }}" alt="{{ $product->name }}" />
               @endforeach
            </div>
         @else
            <div class="col-12 col-md-8">
               <x-alert type="danger">
                  <p class="small my-0">Keranjang anda kosong, silahkan belanja terlebih dahulu.</p>
               </x-alert>
            </div>
         @endif

         <div class="col-12 col-md-4">
            <div class="card mb-2 border-0 py-2 px-3 shadow">
               <p class="my-0">Dikirim ke</p>
               <hr class="my-2">
               @if ($address)
                  <ul class="list-unstyled small my-0">
                     <x-input type="hidden" id="destination" value="{{ $address->city_id }}" label="" />
                     <x-input type="hidden" id="address" value="{{ $address->address }}" label="" />
                     <x-input type="hidden" id="sub_district" value="{{ $address->sub_district }}" label="" />
                     <x-input type="hidden" id="city" value="{{ $address->city }}" label="" />
                     <x-input type="hidden" id="province" value="{{ $address->province }}" label="" />
                     <x-input type="hidden" id="postal_code" value="{{ $address->postal_code }}" label="" />

                     <li>{{ auth()->user()->name }}</li>
                     <li>{{ $address->address }}</li>
                     <li>{{ $address->sub_district }}</li>
                     <li>{{ $address->city }}</li>
                     <li>{{ $address->province }}</li>
                     <li>{{ $address->postal_code }}</li>
                  </ul>
               @else
                  <x-alert type="danger">
                     <p class="small my-0">Atur alamat utama anda di halaman profil</p>
                  </x-alert>
               @endif
            </div>
            <div class="card small border-0 py-2 px-3 shadow">
               <p class="my-0">Ekspedisi</p>
               <hr class="my-2">
               <x-select class="mb-2" id="courier" label="Kurir" disabled>
                  <x-slot:options>
                     <option value="">-- Pilih kurir --</option>
                     <option value="jne">JNE</option>
                     <option value="pos">POS</option>
                     <option value="tiki">TIKI</option>
                  </x-slot:options>
               </x-select>
               <x-select class="mb-2" id="service" label="Layanan" disabled>
                  <x-slot:options>
                     <option value="">-- Pilih Service --</option>
                  </x-slot:options>
               </x-select>

               <p class="my-0 mb-1" id="view-cost">Ongkir: -</p>
               <p class="my-0 mb-1" id="view-etd">Estimasi (Hari): -</p>
               <p class="my-0 mb-1" id="view-total">Total: -</p>

               <x-input type="hidden" id="cost" value="" label="" />
               <x-input type="hidden" id="etd" value="" label="" />
               <x-input type="hidden" id="total" value="" label="" />

               <div class="d-flex justify-content-end my-2">
                  <x-button type="submit" class="btn-secondary px-3" id="btn-checkout" disabled>
                     <i class="fa-solid fa-bag-shopping"></i> Checkout
                  </x-button>
               </div>
            </div>
         </div>
      </form>
   </div>

   <!-- Modal checkout -->
   <x-modal id="checkout" class="modal-fullscreen" icon="check" button="Oke">
      <x-alert type="success">
         <h6 class="alert-heading small text-center">Anda bisa melakukan pembayaran melalui salah satu bank di bawah ini.</h6>
         <hr>
         <p class="small my-0 text-center">Jika anda sudah melakukan pembayaran, segera konfirmasi di menu
            <a href="/payment-confirm" class="alert-link">Konfirmasi</a> agar pesanan segera diproses.
         </p>
      </x-alert>
      <x-alert type="danger">
         <p class="small my-0 text-center">Segera lakukan pembayaran sebelum</p>
         <p id="count-down-deadline" class="small fs-5 fw-semibold my-0 text-center"></p>
      </x-alert>
      <div class="row small">
         <div class="col-md-4 mb-2">
            <div class="card card-body">
               <h6 class="card-title">BRI</h6>
               <p class="card-text mb-0">No. Rekening : 123456789</p>
               <p class="card-text mb-0">Atas Nama : Andrian Syah</p>
            </div>
         </div>
         <div class="col-md-4 mb-2">
            <div class="card card-body">
               <h6 class="card-title">BCA</h6>
               <p class="card-text mb-0">No. Rekening : 123456789</p>
               <p class="card-text mb-0">Atas Nama : Andrian Syah</p>
            </div>
         </div>
         <div class="col-md-4 mb-2">
            <div class="card card-body">
               <h6 class="card-title">Mandiri</h6>
               <p class="card-text mb-0">No. Rekening : 123456789</p>
               <p class="card-text mb-0">Atas Nama : Andrian Syah</p>
            </div>
         </div>
      </div>
      <hr>
      <div class="row justify-content-start justify-content-md-between align-items-end small">
         <div class="col-12 col-md-6">
            <div class="mb-3">
               <p class="mt-0 mb-1" id="invoice">Invoice:</p>
               <p class="mt-0 mb-1" id="order-date">Tanggal Pesanan:</p>
               <p class="mt-0 mb-1">Dikirim Ke:</p>
               <ul class="list-unstyled mt-0 mb-1">
                  <li>{{ auth()->user()->name }}</li>
                  <li id="address"></li>
               </ul>
            </div>
         </div>
         <div class="col-12 col-md-6">
            <div class="text-start text-md-end mb-3">
               <p class="mt-0 mb-1" id="courier">Kurir:</p>
               <p class="mt-0 mb-1" id="service">Jenis Layanan:</p>
               <p class="mt-0 mb-1" id="estimate">Estimasi (Hari):</p>
               <p class="mt-0 mb-1" id="deadline-date">Batas Waktu Pembayaran:</p>
            </div>
         </div>
      </div>
      <hr>
      <div class="row small">
         <x-table id="table-product-checkout">
            <x-slot:thead>
               <th scope="col">#</th>
               <th scope="col">Produk</th>
               <th scope="col">Warna</th>
               <th scope="col">Ukuran</th>
               <th scope="col">Harga</th>
               <th scope="col">Jumlah</th>
               <th scope="col">Sub Total</th>
               <th scope="col">Catatan</th>
            </x-slot:thead>
         </x-table>
         <div class="text-end mt-3">
            <h6 id="cost">Ongkir</h6>
            <h5 id="total">Total</h5>
         </div>
      </div>
   </x-modal>
@endsection
@push('script')
   <script type="module" src="{{ asset('js/cart.js') }}"></script>
@endpush
