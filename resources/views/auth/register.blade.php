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
                              <h5 class="mb-4">Buat Akun Baru</h5>
                           </div>

                           <!-- error validation -->
                           @if ($errors->any())
                              <x-alert type="danger">
                                 <p class="my-2">Oops, Register gagal!</p>
                                 @foreach ($errors->all() as $error)
                                    <p class="small my-0">{{ $error }}</p>
                                 @endforeach
                              </x-alert>
                           @endif

                           <form method="POST" action="{{ route('register') }}" class="small">
                              @csrf
                              <div class="mb-2">
                                 <x-input type="text" id="name" value="{{ old('name') }}" label="Nama Lengkap" placeholder="Masukan nama lengkap"  autofocus />
                              </div>
                              <div class="mb-2">
                                 <x-input type="email" id="email" value="{{ old('email') }}" label="Email" placeholder="Masukan email"  />
                              </div>
                              <div class="mb-2">
                                 <x-input type="password" id="password" value="" label="Password" placeholder="Masukan password"  />
                              </div>

                              <div class="mb-2">
                                 <x-input type="password" id="password_confirmation" value="" label="Konfirmasi Password" placeholder="Masukan password lagi"  />
                              </div>
                              <div class="d-flex justify-content-center align-items-center mt-4">
                                 <x-button type="submit" class="btn-primary px-4"><i class="fa-solid fa-user-plus"></i> Register</x-button>
                              </div>
                              <hr>
                              <div class="text-center">
                                 <span>
                                    Sudah punya akun?
                                    <a href="{{ route('login') }}" class="text-decoration-none">Login</a>
                                 </span>
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
