<nav class="navbar navbar-expand navbar-light topbar static-top mb-4 bg-white shadow">

   <!-- Sidebar Toggle (Topbar) -->
   <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
      <i class="fa fa-bars"></i>
   </button>

   <h4>{{ $title }}</h4>

   <!-- Topbar Navbar -->
   <ul class="navbar-nav ml-auto">
      <!-- Nav Item - User Information -->
      <li class="nav-item dropdown no-arrow">
         <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
            <span class="d-none d-lg-inline small mr-2 text-gray-600">{{ auth()->user()->name }}</span>
            <img class="img-profile rounded-circle" src="{{ asset('storage/img/' . auth()->user()->image) }}">
         </a>
         <!-- Dropdown - User Information -->
         <div class="dropdown-menu dropdown-menu-right animated--grow-in shadow" aria-labelledby="userDropdown">
            <form action="{{ route('logout-admin') }}" method="post">
               @csrf
               <button type="submit" class="dropdown-item">
                  <i class="fa-solid fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                  Logout
               </button>
            </form>
         </div>
      </li>

   </ul>

</nav>