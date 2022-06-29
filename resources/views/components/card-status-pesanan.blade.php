<div class="col-6 col-md-12 col-lg-4 my-2">
   <a href="/orders/status/{{ $link }}" class="text-decoration-none link-secondary">
      <div class="card position-relative border-0 p-3 shadow-sm h-100">
         <span class="position-absolute start-100 translate-middle badge text-bg-secondary top-0">{{ $count }}</span>
         <div class="d-flex justify-content-between align-items-center h-100">
            <p class="small my-0">{{ $status }}</p>
            <i class="fa-solid fa-{{ $icon }} fa-xl"></i>
         </div>
      </div>
   </a>
</div>