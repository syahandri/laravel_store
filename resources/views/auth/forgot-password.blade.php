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
                              <h5 class="mb-2">Lupa password anda?</h5>
                              <p class="small mb-4">Jangan khawatir, silahkan isi email anda dan kami akan mengirim tautan untuk mereset password anda.</p>
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
                                 @foreach ($errors->all() as $error)
                                    <p class="small my-0">{{ $error }}</p>
                                 @endforeach
                              </x-alert>
                           @endif
                           <form method="POST" action="{{ route('password.email') }}">
                              @csrf
                              <div class="mb-2">
                                 <x-input type="email" id="email" value="{{ old('email') }}" label="" placeholder="Masukan email" required autofocus />
                              </div>
                              <x-button type="submit" class="btn-primary px-3"><i class="fa-solid fa-arrows-rotate me-1"></i> Reset Password</x-button>
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
