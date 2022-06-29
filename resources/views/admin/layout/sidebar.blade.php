<ul class="navbar-nav bg-gradient-dark sidebar sidebar-dark accordion" id="accordionSidebar">

   <!-- Sidebar - Brand -->
   <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
      <div class="sidebar-brand-icon">
         <i class="fa-solid fa-shop fa-lg"></i>
      </div>
      <div class="sidebar-brand-text mx-2">Laravel Store</div>
   </a>

   <!-- Divider -->
   <hr class="sidebar-divider my-0">

   <!-- Nav Item - Dashboard -->
   <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
      <a class="nav-link" href="{{ route('dashboard') }}">
         <i class="fa-solid fa-gauge-high"></i>
         <span>Dashboard</span></a>
   </li>

   <!-- Divider -->
   <hr class="sidebar-divider">

   <!-- Heading -->
   <div class="sidebar-heading">
      Kelola Produk & Kategori
   </div>

   <!-- Nav Item - Product Collapse Menu -->
   <li class="nav-item {{ request()->routeIs('product.*') || request()->routeIs('category.*') ? 'active' : '' }}">
      <a class="nav-link show" href="#" data-bs-toggle="collapse" data-toggle="collapse" data-bs-target="#product-collapse">
         <i class="fa-solid fa-fw fa-box"></i>
         <span>Produk & Kategori</span>
      </a>
      <div id="product-collapse" class="collapse show">
         <div class="collapse-inner rounded bg-white py-2">
            <h6 class="collapse-header">Produk & Kategori</h6>
            <a class="collapse-item" href="{{ route('product.index') }}">List Produk</a>
            <a class="collapse-item" href="{{ route('category.index') }}">List Kategori</a>
         </div>
      </div>
   </li>

   <!-- Divider -->
   <hr class="sidebar-divider">

   <!-- Heading -->
   <div class="sidebar-heading">
      Kelola Pesanan
   </div>

   <!-- Nav Item - Order Collapse Menu -->
   <li class="nav-item {{ request()->routeIs('order.*') ? 'active' : '' }}">
      <a class="nav-link show" href="#" data-bs-toggle="collapse" data-toggle="collapse" data-bs-target="#order-collapse">
         <i class="fa-solid fa-fw fa-box-open"></i>
         <span>Pesanan</span>
      </a>
      <div id="order-collapse" class="collapse show">
         <div class="collapse-inner rounded bg-white py-2">
            <h6 class="collapse-header">List Pesanan</h6>
            <a class="collapse-item" href="{{ route('order.pending') }}">Pending</a>
            <a class="collapse-item" href="{{ route('order.confirm') }}">Konfirmasi</a>
            <a class="collapse-item" href="{{ route('order.proccess') }}">Diproses</a>
            <a class="collapse-item" href="{{ route('order.sent') }}">Dikirim</a>
            <a class="collapse-item" href="{{ route('order.cancel') }}">Dibatalkan</a>
         </div>
      </div>
   </li>

   <!-- Divider -->
   <hr class="sidebar-divider">

   <!-- Heading -->
   <div class="sidebar-heading">
      Kelola Laporan
   </div>

   <!-- Nav Item - Report Collapse Menu -->
   <li class="nav-item {{ request()->routeIs('report.*') ? 'active' : '' }}">
      <a class="nav-link show" href="#" data-bs-toggle="collapse" data-toggle="collapse" data-bs-target="#report-collapse">
         <i class="fa-solid fa-fw fa-file-invoice"></i>
         <span>Laporan</span>
      </a>
      <div id="report-collapse" class="collapse show">
         <div class="collapse-inner rounded bg-white py-2">
            <h6 class="collapse-header">Laporan Transaksi</h6>
            <a class="collapse-item" href="{{ route('report.sales') }}">Penjualan</a>
         </div>
      </div>
   </li>

   <!-- Divider -->
   <hr class="sidebar-divider d-none d-md-block">

   <!-- Sidebar Toggler (Sidebar) -->
   <div class="d-none d-md-inline text-center">
      <button class="rounded-circle border-0" id="sidebarToggle"></button>
   </div>

</ul>
