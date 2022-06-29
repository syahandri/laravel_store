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

<body class="bg-primary">
   <!-- Konten -->
   @yield('content')
</body>

</html>
