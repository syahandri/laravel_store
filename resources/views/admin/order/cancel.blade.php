@extends('admin.layout.main')
@section('content')
   <div class="row align-items-center">
      <div class="col-12">
         <div class="card card-body border-0 shadow">
            <x-table id="table-cancel">
               <x-slot:thead>
                  <th scope="col">#</th>
                  <th scope="col">Invoice</th>
                  <th scope="col">Tanggal Pesan</th>
                  <th scope="col">Tanggal Batal</th>
                  <th scope="col">Alasan Batal</th>
                  <th scope="col">Opsi</th>
               </x-slot:thead>
            </x-table>
         </div>
      </div>
   </div>
   <x-modal class="modal-lg modal-dialog-scrollable" id="cancel" icon="check" button="Oke"></x-modal>
@endsection
@push('script')
   <script type="module" src="{{ asset('js/admin/order.js') }}"></script>
@endpush
