@extends('guest.layout.main')
@section('content')
   <div class="container py-4">
      <h5 class="mb-4">{{ $title }}</h5>
      <div class="row justify-content-start justify-content-md-between">
         <div class="col-12 col-md-4 small mb-2">
            <form action="/payment-confirm/confirm" id="form-confirm" method="post" enctype="multipart/form-data">
               @csrf
               <input type="hidden" name="inv">
               <x-select class="mb-1" id="invoice" label="Invoice">
                  <x-slot:options>
                     <option value="">-- Pilih Invoice --</option>
                     @foreach ($checkouts as $checkout)
                        <option value="{{ $checkout->id }}">{{ $checkout->invoice }}</option>
                     @endforeach
                  </x-slot:options>
               </x-select>
               <div class="invalid-feedback invalid-invoice"></div>

               <p class="mb-1 mt-2">Transfer ke Bank</p>
               <div class="form-check form-check-inline">
                  <x-check-radio type="radio" id="bri" name="bank" value="BRI" label="BRI" checked required />
               </div>
               <div class="form-check form-check-inline">
                  <x-check-radio type="radio" id="bca" name="bank" value="BCA" label="BCA" required />
               </div>
               <div class="form-check form-check-inline">
                  <x-check-radio type="radio" id="bni" name="bank" value="BNI" label="BNI" required />
               </div>
               <div class="form-check form-check-inline">
                  <x-check-radio type="radio" id="mandiri" name="bank" value="MANDIRI" label="MANDIRI" required />
                  <div class="invalid-feedback invalid-bank"></div>
               </div>

               <x-input class="mb-1 text-capitalize" type="text" id="name" label="Atas Nama" placeholder="Misal: John" required />
               <div class="invalid-feedback invalid-name"></div>

               <x-input class="mb-1" type="file" id="image" label="Bukti Transfer" required />
               <span class="small text-secondary d-block">*Ukuran maks. 1MB, berupa JPG/JPEG/PNG</span>
               <div class="invalid-feedback invalid-image"></div>

               <x-button type="submit" class="btn-primary mt-3 px-3">
                  <i class="fa-solid fa-check"></i> Konfirmasi
               </x-button>
            </form>
         </div>
         <div class="col-12 col-md-8 small mb-2">
            <x-table id="table-detail">
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
      </div>
   </div>
@endsection
@push('script')
   <script type="module" src="{{ asset('js/confirm.js') }}"></script>
@endpush
