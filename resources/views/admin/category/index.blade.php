@extends('admin.layout.main')
@section('content')
   <div class="row align-items-center">
      <div class="col-12">
         <div class="card card-body border-0 shadow">
            <div class="mb-3">
               <x-button type="button" class="btn-primary" id="btn-add"><i class="fa-solid fa-plus"></i> Tambah Kategori</x-button>
            </div>
            <div class="row align-items-center">
               @forelse ($categories as $category)
                  <div class="col-12 col-md-6 col-lg-4 mb-3">
                     <div class="card text-bg-secondary rounded-3 border-0 bg-opacity-10 py-2 px-3 shadow-sm">
                        <div class="row justify-content-between align-items-center text-dark">
                           <div class="col-10">
                              <p class="my-0">{{ $category->name }}</p>
                              <span class="small">{{ $category->products_count }} Produk</span>
                           </div>
                           <div class="col-2 text-end dropdown no-arrow">
                              <button class="btn dropdown-toggle border-0" data-bs-toggle="dropdown">
                                 <i class="fa-solid fa-ellipsis-vertical"></i>
                              </button>
                              <div class="dropdown-menu shadow">
                                 <a class="dropdown-item text-secondary btn-edit" href="javascript:void(0)" data-id="{{ $category->slug }}">
                                    <i class="fa-solid fa-pencil"></i> Edit kategori
                                 </a>
                                 <a class="dropdown-item text-secondary btn-delete" href="javascript:void(0)" data-id="{{ $category->slug }}">
                                    <i class="fa-solid fa-trash"></i> Hapus kategori
                                 </a>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               @empty
                  <div class="col-12">
                     <x-alert type="warning"><span class="small">Tidak ada Kategori!</span></x-alert>
                  </div>
               @endforelse
            </div>
         </div>
      </div>
   </div>

   <x-modal id="category" icon="save" button="Simpan">
      <x-input type="text" class="text-capitalize rounded-3" id="name" label="Nama Kategori" placeholder="Nama kategori..." required/>
      <div class="invalid-feedback"></div>
   </x-modal>
@endsection
@push('script')
   <script type="module" src="{{ asset('js/admin/category.js') }}"></script>
@endpush
