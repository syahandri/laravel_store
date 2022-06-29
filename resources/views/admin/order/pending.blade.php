@extends('admin.layout.main')
@section('content')
   <div class="row align-items-center">
      <div class="col-12">
         <div class="card card-body border-0 shadow">
            <x-table id="table-pending">
               <x-slot:thead>
                  <th scope="col">#</th>
                  <th scope="col">Invoice</th>
                  <th scope="col">Tanggal Pesan</th>
                  <th scope="col">Batas Waktu</th>
                  <th scope="col">Status</th>
                  <th scope="col">Opsi</th>
               </x-slot:thead>
            </x-table>
         </div>
      </div>
   </div>
   <x-modal class="modal-lg" id="pending" icon="check" button="Oke"></x-modal>
@endsection
@push('script')
   <script type="module" src="{{ asset('js/admin/order.js') }}"></script>
@endpush
