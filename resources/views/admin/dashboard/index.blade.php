@extends('admin.layout.main')
@section('content')
   <!-- Info produk dan kategori -->
   <div class="row align-items-center small">
      <div class="col-12 col-sm-6 mb-4">
         <div class="card card-body border-left-primary h-100 border-0 shadow">
            <div class="row justify-content-between align-items-center">
               <div class="col-9">
                  <p class="fw-semibold my-0">Jumlah Produk</p>
                  <p class="my-0">{{ $product_count }} Produk</p>
               </div>
               <div class="col-3 text-end">
                  <i class="fa-solid fa-box fa-2xl"></i>
               </div>
            </div>
         </div>
      </div>
      <div class="col-12 col-sm-6 mb-4">
         <div class="card card-body border-left-warning h-100 border-0 shadow">
            <div class="row justify-content-between align-items-center">
               <div class="col-9">
                  <p class="fw-semibold my-0">Jumlah Kategori</p>
                  <p class="my-0">{{ $category_count }} Kategori</p>
               </div>
               <div class="col-3 text-end">
                  <i class="fa-solid fa-list-ul fa-2xl"></i>
               </div>
            </div>
         </div>
      </div>
   </div>

   <!-- Info Pesanan -->
   <div class="row align-items-center small">
      <div class="col-12 col-sm-6 col-lg-4 mb-4">
         <div class="card card-body border-left-info h-100 border-0 shadow">
            <div class="row justify-content-between align-items-center">
               <div class="col-9">
                  <p class="fw-semibold my-0">Pesanan Pending</p>
                  <p class="my-0">{{ $pending_count }} Pesanan</p>
               </div>
               <div class="col-3 text-end">
                  <i class="fa-solid fa-clock fa-2xl text-warning"></i>
               </div>
            </div>
         </div>
      </div>
      <div class="col-12 col-sm-6 col-lg-4 mb-4">
         <div class="card card-body border-left-info h-100 border-0 shadow">
            <div class="row justify-content-between align-items-center">
               <div class="col-9">
                  <p class="fw-semibold my-0">Pesanan Dikonfirmasi</p>
                  <p class="my-0">{{ $confirmed_count }} Pesanan</p>
               </div>
               <div class="col-3 text-end">
                  <i class="fa-solid fa-circle-check fa-2xl text-info"></i>
               </div>
            </div>
         </div>
      </div>
      <div class="col-12 col-sm-6 col-lg-4 mb-4">
         <div class="card card-body border-left-primary h-100 border-0 shadow">
            <div class="row justify-content-between align-items-center">
               <div class="col-9">
                  <p class="fw-semibold my-0">Pesanan Diproses</p>
                  <p class="my-0">{{ $proccess_count }} Pesanan</p>
               </div>
               <div class="col-3 text-end">
                  <i class="fa-solid fa-box fa-2xl text-primary"></i>
               </div>
            </div>
         </div>
      </div>
      <div class="col-12 col-sm-6 col-lg-4 mb-4">
         <div class="card card-body border-left-success h-100 border-0 shadow">
            <div class="row justify-content-between align-items-center">
               <div class="col-9">
                  <p class="fw-semibold my-0">Pesanan Dikirim</p>
                  <p class="my-0">{{ $sent_count }} Pesanan</p>
               </div>
               <div class="col-3 text-end">
                  <i class="fa-solid fa-truck fa-2xl text-success"></i>
               </div>
            </div>
         </div>
      </div>
      <div class="col-12 col-sm-6 col-lg-4 mb-4">
         <div class="card card-body border-left-danger h-100 border-0 shadow">
            <div class="row justify-content-between align-items-center">
               <div class="col-9">
                  <p class="fw-semibold my-0">Pesanan Dibatalkan</p>
                  <p class="my-0">{{ $cancel_count }} Pesanan</p>
               </div>
               <div class="col-3 text-end">
                  <i class="fa-solid fa-circle-xmark fa-2xl text-danger"></i>
               </div>
            </div>
         </div>
      </div>
   </div>

   <!-- Info Produk Terlaris -->
   <div class="row align-items-center">
      <div class="col-12">
         <div class="card card-body border-0 shadow">
            <h6 class="fw-semibold mb-3">Produk Terlaris</h6>
            <x-table id="table-bestseller-product">
               <x-slot:thead>
                  <th scope="col">#</th>
                  <th scope="col">Produk</th>
                  <th scope="col">Harga</th>
                  <th scope="col">Diskon</th>
                  <th scope="col">Stok</th>
                  <th scope="col">Terjual</th>
               </x-slot:thead>
               @foreach ($bestseller as $product)
                  <tr>
                     <td>{{ $loop->iteration }}</td>
                     <td>{{ $product->name }}</td>
                     <td>Rp. {{ number_format($product->price, '0', ',', '.') }}</td>
                     <td>{{ $product->discount }}%</td>
                     <td>{{ $product->stock }}</td>
                     <td>{{ $product->sold }}</td>
                  </tr>
               @endforeach
            </x-table>
         </div>
      </div>
   </div>
@endsection
