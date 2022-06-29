@extends('admin.layout.main')
@section('content')
   <div class="row align-items-center">
      <div class="col-12">
         <div class="card card-body border-0 shadow">
            <x-table id="table-sales">
               <x-slot:thead>
                  <th scope="col">#</th>
                  <th scope="col">Tanggal</th>
                  <th scope="col">Customer</th>
                  <th scope="col">Produk</th>
                  <th scope="col">Harga</th>
                  <th scope="col">Jumlah Beli</th>
               </x-slot:thead>
            </x-table>
         </div>
      </div>
   </div>
@endsection
@push('script')
   <script>
      const table_sales = $('#table-sales').DataTable({
         serverSide: true,
         ajax: '/store-admin/report/sales',
         dom: "<'row mb-3'<'col-sm-6 d-flex justify-content-center justify-content-sm-start'B>>" +
            "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
         buttons: [{
            className: 'btn btn-primary btn-sm rounded',
            extend: 'pdf',
            pageSize: 'A4',
            text: '<i class="fa-solid fa-file-pdf"></i> Simpan PDF',
         }],
         columns: [{
               data: 'DT_RowIndex',
               name: 'id',
               orderable: false,
               searchable: false
            },
            {
               data: 'sent_date',
               name: 'sent_date'
            },
            {
               data: 'user',
               name: 'user'
            },
            {
               data: 'product',
               name: 'product'
            },
            {
               data: 'price',
               name: 'price'
            },
            {
               data: 'quantity',
               name: 'quantity'
            }
         ]
      })
   </script>
@endpush
