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

   <!-- SB Admin CSS -->
   <link rel="stylesheet" href="{{ asset('sbadmin/css/sb-admin-2.min.css') }}">

   <!-- DataTables CSS-->
   <link rel="stylesheet" href="{{ asset('DataTables/DataTables/css/dataTables.bootstrap5.min.css') }}">
   <link rel="stylesheet" href="{{ asset('DataTables/FixedColumn/css/fixedColumns.bootstrap5.min.css') }}">
   <link rel="stylesheet" href="{{ asset('DataTables/FixedHeader/css/fixedHeader.bootstrap5.min.css') }}">
   <link rel="stylesheet" href="{{ asset('DataTables/DataTables/css/buttons.bootstrap5.min.css') }}">

   <!-- Trix CSS -->
   <link rel="stylesheet" href="{{ asset('css/admin/trix.css') }}">

   <!-- Style CSS -->
   <link rel="stylesheet" href="{{ asset('/css/style.css') }}">

   <link rel="shortcut icon" href="{{ asset('laravel.ico') }}" type="image/x-icon">

   <title>{{ $title }}</title>
</head>

<body id="page-top" class="position-relative">

   <!-- Page Wrapper -->
   <div id="wrapper">

      <!-- Sidebar -->
      @include('admin.layout.sidebar')
      <!-- End of Sidebar -->

      <!-- Content Wrapper -->
      <div id="content-wrapper" class="d-flex flex-column">

         <!-- Main Content -->
         <div id="content">

            <!-- Topbar -->
            @include('admin.layout.topbar')
            <!-- End of Topbar -->

            <!-- Begin Page Content -->
            <div class="container-fluid">
               @yield('content')
            </div>
            <!-- /.container-fluid -->

         </div>
         <!-- End of Main Content -->

         <!-- Footer -->
         @include('admin.layout.footer')
         <!-- End of Footer -->

      </div>
      <!-- End of Content Wrapper -->

   </div>
   <!-- End of Page Wrapper -->

   <!-- Scroll to Top Button-->
   <a class="scroll-to-top rounded" href="#page-top">
      <i class="fas fa-angle-up"></i>
   </a>

   <!-- JQuery -->
   <script src="{{ asset('sbadmin/jquery/jquery.min.js') }}"></script>

   <!-- Bootstrap JS -->
   <script src="{{ asset('bootstrap/js/bootstrap.bundle.min.js') }}"></script>

   <!-- Font Awesome JS -->
   <script src="{{ asset('fontawesome/js/all.min.js') }}"></script>

   <!-- Sweet Alert -->
   <script src="{{ asset('sweetalert/sweetalert.min.js') }}"></script>

   <!-- Core plugin JavaScript-->
   <script src="{{ asset('sbadmin/jquery-easing/jquery.easing.min.js') }}"></script>

   <!-- Custom scripts for all pages-->
   <script src="{{ asset('sbadmin/js/sb-admin-2.min.js') }}"></script>

   <!-- DataTables JS -->
   <script src="{{ asset('DataTables/DataTables/js/jquery.dataTables.min.js') }}"></script>
   <script src="{{ asset('DataTables/DataTables/js/pdfmake.min.js') }}"></script>
   <script src="{{ asset('DataTables/DataTables/js/vfs_fonts.js') }}"></script>
   <script src="{{ asset('DataTables/DataTables/js/dataTables.buttons.min.js') }}"></script>
   <script src="{{ asset('DataTables/DataTables/js/dataTables.bootstrap5.min.js') }}"></script>
   <script src="{{ asset('DataTables/DataTables/js/buttons.html5.min.js') }}"></script>
   <script src="{{ asset('DataTables/DataTables/js/buttons.print.min.js') }}"></script>
   <script src="{{ asset('DataTables/FixedColumn/js/fixedColumns.bootstrap5.min.js') }}"></script>
   <script src="{{ asset('DataTables/FixedHeader/js/fixedHeader.bootstrap5.min.js') }}"></script>

   <!-- Trix JS -->
   <script src="{{ asset('js/admin/trix.js') }}"></script>

   @stack('script')

   <!-- loading -->
   <div class="d-none position-absolute start-0 w-100 h-100 text-bg-dark top-0 bg-opacity-75" style="z-index: 10" id="main-loading">
      <div class="flex-wrap py-5 text-center">
         <h1 class="pt-5 pb-3">Mohon Tunggu...</h1>
         <span class="spinner-border text-light d-block m-auto" role="status" style="width: 3rem; height: 3rem;"></span>
      </div>
   </div>
</body>

</html>
