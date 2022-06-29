@extends('guest.layout.main')
@section('content')
   <div class="container py-4">
      <div class="row">
         <h5 class="mb-3">{{ $title }} / Menunggu Diproses</h5>
         <div class="col-12">
            @forelse ($orders as $order)
               <div class="card small rounded-4 mb-3 border-0 p-3 shadow">
                  <div class="row justify-content-between align-items-end">
                     <div class="col-12 col-md-6">
                        <p class="my-0">Invoice: {{ $order->invoice }}</p>
                        <p class="my-0">Konfirmasi Atas Nama: {{ $order->confirm->name }}</p>
                     </div>
                     <div class="col-12 col-md-6 text-start text-md-end">
                        <p class="my-0">Tanggal Pesan: {{ $order->order_date }}</p>
                        <p class="my-0">Tanggal Konfirmasi: {{ $order->confirm_date }}</p>
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
                  <div class="row justify-content-end">
                     <div class="col-12 text-end mt-sm-0 mt-2">
                        <p class="fw-semibold my-0">Ongkir : Rp. {{ number_format($order->cost, '0', ',', '.') }}</p>
                        <p class="fw-semibold my-0 mb-2">Total : Rp. {{ number_format($order->total, '0', ',', '.') }}</p>
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
@endsection
