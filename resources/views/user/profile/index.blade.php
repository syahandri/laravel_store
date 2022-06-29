@extends('guest.layout.main')
@section('content')
   <div class="container py-4">
      <div class="row">

         <!-- Profil -->
         <div class="col-12 col-md-6 col-lg-4 mb-4">
            <div class="card bg-light rounded-4 overflow-hidden border-0 shadow p-3">
               <img src="{{ asset('storage/img/' . $user->image) }}" alt="" class="img-fluid img-profile d-block m-auto rounded-circle" loading="lazy">
               <div class="row justify-content-between p-2">
                  <p class="fw-semibold text-capitalize mt-2 mb-3 text-center">{{ $user->name }}</p>
                  <div class="col-4">
                     <ul class="list-unstyled small text-muted">
                        <li class="my-1">Telepon</li>
                        <li class="my-1">Email</li>
                     </ul>
                  </div>
                  <div class="col-8">
                     <ul class="list-unstyled small text-muted text-end">
                        <li class="my-1">{{ $user->phone ?? '---' }}</li>
                        <li class="my-1">{{ $user->email }}</li>
                     </ul>
                  </div>
                  <div class="d-flex justify-content-between">
                     <x-button type="button" class="btn-success" id="btn-edit-profile" data-id="{{ $user->id }}">
                        <i class="fa-solid fa-pen"></i> Edit profil
                     </x-button>
                     <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-button type="submit" class="btn-outline-danger"><i class="fa-solid fa-sign-out"></i> Logout</x-button>
                     </form>
                  </div>
               </div>
            </div>
         </div>

         <!-- Alamat -->
         <div class="col-12 col-md-6 col-lg-8">
            <div class="card bg-light rounded-4 mb-4 overflow-hidden border-0 shadow">
               <div class="row justify-content-between p-3">
                  <p class="fw-semibold text-capitalize my-0">Alamat</p>
                  <hr class="my-2 mb-3">
                  <div class="col">
                     <ul class="list-unstyled small my-0">
                        @forelse ($user->addresses as $address)
                           <li class="my-2">
                              <a href="javascript:void(0)" class="{{ $address->flags == 1 ? 'active' : '' }} text-decoration-none text-muted btn-edit-address"
                                 data-id="{{ $address->id }}">
                                 @if ($address->flags == 1)
                                    <i class="fa-solid fa-check-circle"></i>
                                 @endif
                                 {{ $address->address }}, {{ $address->sub_district }}, {{ $address->city }}, {{ $address->province }}, {{ $address->postal_code }}
                              </a>
                           </li>
                        @empty
                           <li class="my-2">Alamat belum ada</li>
                        @endforelse
                     </ul>
                     <div class="d-flex justify-content-between mt-3 flex-wrap">
                        <p class="small fw-lighter text-danger align-self-end my-0">*Klik alamat untuk mengubahnya</p>
                        @if ($user->addresses->count() < 3)
                           <x-button type="button" class="btn-outline-primary" id="add-address"><i class="fa-solid fa-plus"></i> Tambah alamat</x-button>
                        @endif
                     </div>
                  </div>
               </div>
            </div>

            <!-- Status pesanan -->
            <div class="card bg-light rounded-4 mb-4 overflow-hidden border-0 shadow">
               <div class="row p-3">
                  <p class="fw-semibold text-capitalize my-0">Status Pesanan</p>
                  <hr class="my-2 mb-3">
                  <x-card-status-pesanan status="Pending" count="{{ $count_pending }}" icon="clock text-warning" link="pending" />
                  <x-card-status-pesanan status="Dikonfirmasi" count="{{ $count_confirmed }}" icon="circle-check text-info" link="confirmed" />
                  <x-card-status-pesanan status="Diproses" count="{{ $count_proccess }}" icon="box text-primary" link="proccess" />
                  <x-card-status-pesanan status="Dikirim" count="{{ $count_send }}" icon="truck text-success" link="sent" />
                  <x-card-status-pesanan status="Dibatalkan" count="{{ $count_cancel }}" icon="circle-xmark text-danger" link="cancel" />
               </div>
            </div>
         </div>
      </div>
   </div>

   <!-- Modal profil dan alamat -->
   <x-modal class="modal-lg" id="profile" button="Simpan" icon="save" />
   <x-modal class="modal-lg" id="addresses" button="Simpan" icon="save" />
@endsection
@push('script')
   <script type="module" src="{{ asset('js/profile.js') }}"></script>
@endpush
