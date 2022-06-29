@extends('guest.layout.main')
@section('content')
   <div class="container py-4">
      <div class="row">
         <h5 class="mb-3">{{ $title }} / Belum Melakukan Pembayaran</h5>
         <div class="col-12">
            @if ($orders->count())
               <x-alert type="warning">
                  <span class="small">
                     Jika anda sudah melakukan pembayaran, harap segera konfirmasi pembayaran di menu <span class="fw-semibold">Konfirmasi</span>
                  </span>
               </x-alert>
            @endif

            @forelse ($orders as $order)
               <div class="card small rounded-4 mb-3 border-0 p-3 shadow">
                  <div class="row justify-content-between align-items-end">
                     <div class="col-12 col-md-6">
                        <p class="my-0">Invoice: {{ $order->invoice }}</p>
                     </div>
                     <div class="col-12 col-md-6 text-start text-md-end">
                        <p class="my-0">Tanggal Pesan: {{ $order->order_date }}</p>
                        <p class="my-0">Batas Pembayaran: {{ $order->deadline_date }}</p>
                     </div>
                  </div>
                  <hr class="my-2">
                  <x-table id="table-status-pending">
                     <x-slot:thead>
                        <th scope="col">#</th>
                        <th scope="col">Produk</th>
                        <th scope="col">Warna</th>
                        <th scope="col">Ukuran</th>
                        <th scope="col">Catatan</th>
                        <th scope="col">Harga</th>
                        <th scope="col">Jumlah</th>
                        <th scope="col">Sub Total</th>
                     </x-slot:thead>
                     @foreach ($order->products as $product)
                        <tr>
                           <td>{{ $loop->iteration }}</td>
                           <td>{{ $product->name }}</td>
                           <td>{{ $product->pivot->color }}</td>
                           <td>{{ $product->pivot->size }}</td>
                           <td>{{ $product->pivot->note ?? '-' }}</td>
                           <td>Rp. {{ number_format($product->pivot->price, '0', ',', '.') }}</td>
                           <td>{{ $product->pivot->quantity }}</td>
                           <td>Rp. {{ number_format($product->pivot->sub_total, '0', ',', '.') }}</td>
                        </tr>
                     @endforeach
                  </x-table>
                  <div class="row justify-content-between">
                     <div class="col-12 col-sm-6">
                        <p class="my-0">Kurir: {{ $order->courier }}</p>
                        <p class="my-0">Layanan: {{ $order->service }}</p>
                     </div>
                     <div class="col-12 col-sm-6 text-start text-sm-end mt-sm-0 mt-2">
                        <p class="fw-semibold my-0">Ongkir : Rp. {{ number_format($order->cost, '0', ',', '.') }}</p>
                        <p class="fw-semibold my-0 mb-2">Total : Rp. {{ number_format($order->total, '0', ',', '.') }}</p>
                        <x-button type="button" class="btn-success btn-pay px-3" data-invoice="{{ $order->id }}"><i class="fa-solid fa-dollar-sign"></i> Bayar</x-button>
                     </div>
                  </div>
               </div>
            @empty
               <x-alert type="danger">
                  <span class="small">Anda Tidak Memiliki Pesanan!</span>
               </x-alert>
            @endforelse
            {{ $orders->links() }}
         </div>
      </div>
   </div>

   <!-- Modal bayar pesanan -->
   <x-modal id="pending" class="modal-fullscreen" icon="check" button="Oke">
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
         <x-table id="table-product-pending">
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
   <script type="module" src="{{ asset('js/order.js') }}"></script>
@endpush
