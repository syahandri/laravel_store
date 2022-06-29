@extends('admin.layout.main')
@section('content')
   <div class="row align-items-center">
      <div class="col-12">
         <div class="card card-body border-0 shadow">
            <div class="mb-4">
               <a href="{{ route('product.create') }}"class="btn btn-sm btn-primary"><i class="fa-solid fa-plus"></i> Tambah Produk</a>
            </div>

            @if (session('message'))
               <x-alert type="success" class="alert-dismissible fade show">
                  {{ session('message') }}
                  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
               </x-alert>
            @endif

            <x-table id="table-product">
               <x-slot:thead>
                  <th scope="col">#</th>
                  <th scope="col">Produk</th>
                  <th scope="col">Harga</th>
                  <th scope="col">Diskon</th>
                  <th scope="col">Stok</th>
                  <th scope="col">Berat</th>
                  <th scope="col">Terjual</th>
                  <th scope="col">Opsi</th>
               </x-slot:thead>
            </x-table>
         </div>
      </div>
   </div>

   <x-modal class="modal-lg modal-dialog-scrollable" id="product" icon="check" button="Oke"></x-modal>
@endsection
@push('script')
   <script type="module" src="{{ asset('js/admin/product.js') }}"></script>
@endpush
