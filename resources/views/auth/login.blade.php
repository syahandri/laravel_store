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
                              <h5 class="mb-4">Selamat Datang!</h5>
                           </div>

                           <!-- session status -->
                           @if (session('status'))
                              <x-alert type="success">
                                 <p class="small my-0">{{ session('status') }}</p>
                              </x-alert>
                           @endif

                           <!-- error validation -->
                           @if ($errors->any())
                              <x-alert type="danger">
                                 <p class="my-2">Oops, Login gagal!</p>
                                 @foreach ($errors->all() as $error)
                                    <p class="small my-0">{{ $error }}</p>
                                 @endforeach
                              </x-alert>
                           @endif

                           <form method="POST" action="{{ route('login') }}" class="small">
                              @csrf
                              <div class="mb-2">
                                 <x-input type="email" id="email" value="{{ old('email') }}" label="Email" placeholder="Masukan email" required autofocus />
                              </div>
                              <div class="mb-2">
                                 <x-input type="password" id="password" value="{{ old('password') }}" label="Password" placeholder="Masukan password" required />
                              </div>
                              <div class="form-check mb-2">
                                 <x-check-radio type="checkbox" id="remember_me" name="remember" value label="Remember Me" />
                              </div>
                              <div class="d-flex justify-content-center align-items-center">
                                 <x-button type="submit" class="btn-primary px-4"><i class="fa-solid fa-sign-in me-1"></i> Login</x-button>
                              </div>
                              <hr>
                              <div class="text-center">
                                 <a href="{{ route('password.request') }}" class="d-block text-decoration-none">Lupa password?</a>
                                 <span>Belum punya akun?
                                    <a href="{{ route('register') }}" class="text-decoration-none">Buat akun baru</a>
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
