<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta http-equiv="X-UA-Compatible" content="ie=edge">

   <!-- Bootstrap CSS -->
   <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">

   <!-- Font Sen -->
   <link rel="preconnect" href="https://fonts.googleapis.com">
   <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
   <link href="https://fonts.googleapis.com/css2?family=Sen&display=swap" rel="stylesheet">

   <!-- Font Awesome CSS-->
   <link rel="stylesheet" href="{{ asset('fontawesome/css/all.min.css') }}">

   <!-- Style CSS -->
   <link rel="stylesheet" href="{{ asset('/css/style.css') }}">

   <link rel="shortcut icon" href="{{ asset('laravel.ico') }}" type="image/x-icon">

   <title>Laravel Store | {{ $title }}</title>
</head>

<body id="home">

   <!-- Navbar -->
   <x-navbar />

   <!-- Konten -->
   @yield('content')

   <!-- Footer -->
   <x-footer />

   <!-- Back to Top -->
   <button type="button" class="btn btn-danger rounded-circle back-to-top"><i class="fa-solid fa-angles-up"></i></button>
   
   <!-- Bootstrap JS -->
   <script src="{{ asset('bootstrap/js/bootstrap.bundle.min.js') }}"></script>
   
   <!-- Font Awesome JS -->
   <script src="{{ asset('fontawesome/js/all.min.js') }}"></script>
   
   <!-- Sweet Alert -->
   <script src="{{ asset('sweetalert/sweetalert.min.js') }}"></script>
   
   <!-- Script Home JS -->
   <script type="module" src="{{ asset('js/home.js') }}"></script>

   @stack('script')
</body>

</html>
