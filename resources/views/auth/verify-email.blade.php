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
                           <div class="text-start">
                              <h5 class="mb-3 text-center">Terima Kasih</h5>
                              <p class="small">Silahkan verifikasi email anda terlebih dahulu dengan klik tautan yang sudah kami kirim ke email anda.</p>
                              <p class="small mb-4">Jika anda belum menerima email, silahkan kirim ulang dengan klik tombol di bawah.</p>
                           </div>
                           <!-- session status -->
                           @if (session('status') == 'verification-link-sent')
                              <x-alert type="success">
                                 <p class="small my-0">Tautan verifikasi baru telah dikirim ke email anda.</p>
                              </x-alert>
                           @endif

                           <form method="POST" action="{{ route('verification.send') }}">
                              @csrf
                              <x-button type="submit" class="btn-primary px-3"><i class="fa-solid fa-paper-plane"></i> Kirim Ulang Tautan</x-button>
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
