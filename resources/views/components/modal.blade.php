<div class="modal fade" id="modal-{{ $id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
   <div {{ $attributes->merge(['class' => 'modal-dialog']) }}>
      <div class="modal-content">
         <div class="modal-header">
            <h6 class="modal-title"></h6>
         </div>
         <div class="modal-body">
            @method('PUT')
            @csrf
            <form action="" method="POST" enctype="multipart/form-data" id="form-{{ $id }}">
               {{ $slot }}
         </div>
         <div class="modal-footer border-0">
            <x-button type="button" data-bs-dismiss="modal"><i class="fa-solid fa-times"></i> Batal</x-button>
            <x-button type="submit" class="btn-primary"><i class="fa-solid fa-{{ $icon }}"></i> {{ $button }}</x-button>
         </div>
         </form>
      </div>
   </div>
</div>
