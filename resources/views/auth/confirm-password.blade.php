@extends('auth.layout.main')
@section('content')
   <div class="container">
      <div class="row justify-content-center">
         <div class="col-11 col-sm-10 col-md-7 col-lg-5">
            <div class="card my-5 border-0 shadow-lg">
               <div class="card-body p-0">
                  <div class="row">
                     <div class="col-12">
                        <div class="p-5">
                           <div class="text-center">
                              <h5 class="mb-4">Ini adalah area yang aman. Silahkan konfirmasi password anda sebelum melanjutkan.</h5>
                           </div>

                           <!-- error validation -->
                           @if ($errors->any())
                              <x-alert type="danger">
                                 @foreach ($errors->all() as $error)
                                    <p class="small my-0">{{ $error }}</p>
                                 @endforeach
                              </x-alert>
                           @endif

                           <form method="POST" action="{{ route('password.confirm') }}" class="small">
                              @csrf
                              <div class="mb-2">
                                 <x-input type="password" id="password" value="" label="Password" placeholder="Masukan password" required />
                              </div>
                              <div class="d-flex justify-content-center align-items-center mt-3">
                                 <x-button type="submit" class="btn-primary px-4"><i class="fa-solid fa-check-double"></i> Konfirmasi</x-button>
                              </div>
                           </form>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
@endsection
