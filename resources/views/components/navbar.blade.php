<nav class="navbar navbar-expand-lg sticky-top navbar-light bg-light py-2 shadow-sm">
   <div class="container">
      <a class="navbar-brand text-danger fs-5 fw-semibold" href="/">
         <i class="fa-brands fa-laravel"></i> Laravel Store
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target=".nav-menu">
         <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse nav-menu w-100">
         <div class="navbar-nav small mx-auto">
            <a id="nav-home" class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="/">Beranda</a>
            <a id="nav-newest" class="nav-link {{ request()->get('q') == 'terbaru' ? 'active' : '' }}" href="/product?q=terbaru">Terbaru</a>
            <a id="nav-best-seller" class="nav-link {{ request()->get('q') == 'terlaris' ? 'active' : '' }}" href="/product?q=terlaris">Terlaris</a>
            <a id="nav-cheapest" class="nav-link {{ request()->get('q') == 'termurah' ? 'active' : '' }}" href="/product?q=termurah">Termurah</a>
            <div class="nav-item dropdown">
               <a class="nav-link dropdown-toggle {{ request()->is('product/category*') ? 'active' : '' }}" href="#" role="button" data-bs-toggle="dropdown">
                  Kategori
               </a>
               <div class="dropdown-menu bg-light border-0 shadow-sm">
                  @foreach ($categories as $category)
                     <a class="dropdown-item small {{ request()->is('product/category/' . $category->slug) ? 'active' : '' }}"
                        href="/product/category/{{ $category->slug }}">{{ $category->name }}</a>
                  @endforeach
               </div>
            </div>
            <a class="nav-link {{ request()->is('payment-confirm') ? 'active' : '' }}" href="/payment-confirm">Konfirmasi</a>
         </div>
      </div>
      <div class="collapse navbar-collapse nav-menu">
         <div class="navbar-nav ms-auto">

            <!-- Visible in lg -->
            <div class="d-none d-lg-flex small">
               @auth
                  <a href="/cart" class="nav-link position-relative" title="Keranjang"><i class="fa-solid fa-cart-shopping fa-xl"></i>
                     <span class="position-absolute start-75 translate-middle badge text-bg-danger top-25 fw-lighter count-cart">{{ $cart_count }}</span>
                  </a>

                  <a class="nav-link" href="/profile" role="button" title="Profil"><i class="fa-solid fa-user-circle fa-xl"></i></a>
               @else
                  <div class="small d-flex align-items-center">
                     <a href="{{ route('login') }}" class="btn btn-outline-danger rounded-pill btn-sm mx-2 px-3">Login</a>
                     <a href="{{ route('register') }}" class="btn btn-warning rounded-pill btn-sm px-3">Register</a>
                  </div>
               @endauth
            </div>
            <!-- Visible in sm -->
            <div class="d-lg-none small">
               @auth
                  <a class="nav-link" href="/cart">Keranjang</a>
                  <a class="nav-link" href="/profile">{{ auth()->user()->name }}</a>
               @else
                  <div class="small d-flex align-items-center my-2">
                     <a href="{{ route('login') }}" class="btn btn-outline-danger rounded-pill btn-sm d-inline me-2 px-3">Login</a>
                     <a href="{{ route('register') }}" class="btn btn-warning rounded-pill btn-sm d-inline px-3">Register</a>
                  </div>
               @endauth
            </div>
         </div>
      </div>
   </div>
</nav>
<div class="py-3">
   <div class="container">
      <form action="/product/find" class="w-100">
         <div class="row justify-content-center align-items-center">
            <div class="col-12 col-lg-8">
               <div class="input-group">
                  <input type="text" class="form-control @error('s') is-invalid @enderror" name="s" id="search" placeholder="Cari produk..."
                     value="{{ request()->s }}" required>
                  <x-button type="submit" class="btn-outline-primary rounded-end"><i class="fa-solid fa-magnifying-glass"></i></x-button>
                  <div class="invalid-tooltip">Harap Masukan Kata Kunci Pencarian</div>
               </div>
            </div>
         </div>
      </form>
   </div>
</div>
