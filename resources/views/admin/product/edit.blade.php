@extends('admin.layout.main')
@section('content')
   <div class="row align-items-center">
      <div class="col-12">
         <div class="card card-body border-0 shadow">
            <form action="{{ route('product.update', $product->id_product) }}" method="post" enctype="multipart/form-data">
               @method('put')
               @csrf
               <input type="hidden" name="old_image_1" value="{{ $product->image_1 }}">
               <input type="hidden" name="old_image_2" value="{{ $product->image_2 }}">
               <input type="hidden" name="old_image_3" value="{{ $product->image_3 }}">

               <div class="row mb-2">

                  <!-- input nama -->
                  <div class="col-12 col-sm-6">
                     <x-input type="text" class="text-capitalize rounded" id="name" label="Nama Produk" placeholder="Masukan nama produk" value="{{ old('name', $product->name) }}" required />
                     @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                     @enderror
                  </div>

                  <!-- input harga -->
                  <div class="col-12 col-sm-6">
                     <x-input type="number" class="rounded" id="price" label="Harga Produk" placeholder="Masukan harga produk" min="1" value="{{ old('price', $product->price) }}"
                        required />
                     @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>
                     @enderror
                  </div>

                  <!-- input berat -->
                  <div class="col-12 col-sm-6">
                     <x-input type="number" class="rounded" id="weight" label="Berat Produk (gram)" placeholder="Masukan berat produk" min="1"
                        value="{{ old('weight', $product->weight) }}" required />
                     @error('weight')
                        <div class="invalid-feedback">{{ $message }}</div>
                     @enderror
                  </div>

                  <!-- input diskon -->
                  <div class="col-12 col-sm-6">
                     <x-input type="number" class="rounded" id="discount" label="Diskon Produk (%)" placeholder="Masukan diskon produk" min="0" max="100"
                        value="{{ old('discount', $product->discount) }}" required />
                     @error('discount')
                        <div class="invalid-feedback">{{ $message }}</div>
                     @enderror
                  </div>

                  <!-- input stok -->
                  <div class="col-12 col-sm-6">
                     <x-input type="number" class="rounded" id="stock" label="Stok Produk" placeholder="Masukan stok produk" min="1" value="{{ old('stock', $product->stock) }}"
                        required />
                     @error('stock')
                        <div class="invalid-feedback">{{ $message }}</div>
                     @enderror
                  </div>

                  <!-- input harga setelah diskon -->
                  <div class="col-12 col-sm-6">
                     <x-input type="number" class="rounded" id="discount_price" label="Harga Produk Setelah Diskon" placeholder="harga produk setelah diskon" readonly
                        value="{{ old('discount_price', $product->price - ($product->price * $product->discount) / 100) }}" />
                  </div>

                  <!-- input kategori -->
                  <div class="col-12 col-sm-6">
                     <p class="mt-1 mb-1">Kategori Produk</p>
                     @forelse ($categories as $category)
                        <label class="badge text-bg-light shadow-sm px-2 me-2 mb-2" for="category{{ $category->id }}">
                           <div class="form-check form-check-inline">
                              <input class="form-check-input @error('category') is-invalid @enderror" type="checkbox" id="category{{ $category->id }}" name="category[]" value="{{ $category->id }}" @foreach (old('category', $product->categories) as $cat) @if (old('category') && $cat == $category->id || !old('category') && $cat->id == $category->id) checked @endif @endforeach >
                              <label class="form-check-label" for="category{{ $category->id }}">{{ $category->name }}</label>
                           </div>
                        </label>
                     @empty
                        <x-alert type="warning"><span class="small">Tidak ada kategori!</span></x-alert>
                     @endforelse
                     @error('category')
                        <div class="small text-danger">{{ $message }}</div>
                     @enderror
                  </div>

                  <!-- input ukuran -->
                  <div class="col-12 col-sm-6">
                     <p class="mt-1 mb-1">Varian Ukuran</p>
                     @forelse ($sizes as $size)
                        <label class="badge text-bg-light shadow-sm px-2 me-2 mb-2" for="size{{ $size->id }}">
                           <div class="form-check form-check-inline">
                              <input class="form-check-input @error('size') is-invalid @enderror" type="checkbox" id="size{{ $size->id }}" name="size[]" value="{{ $size->id }}" @foreach (old('size', $product->sizes) as $siz) @if (old('size') && $siz == $size->id || !old('size') && $siz->id == $size->id) checked @endif @endforeach >
                              <label class="form-check-label" for="size{{ $size->id }}">{{ $size->name }}</label>
                           </div>
                        </label>
                     @empty
                        <x-alert type="warning"><span class="small">Tidak ada varian ukuran!</span></x-alert>
                     @endforelse
                     @error('size')
                        <div class="small text-danger">{{ $message }}</div>
                     @enderror
                  </div>

                  <!-- input warna -->
                  <div class="col-12">
                     <p class="mt-1 mb-1">Varian Warna</p>
                     @forelse ($colors as $color)
                        <label class="badge text-bg-light shadow-sm px-2 me-2 mb-2" for="color{{ $color->id }}">
                           <div class="form-check form-check-inline">
                              <input class="form-check-input @error('color') is-invalid @enderror" type="checkbox" id="color{{ $color->id }}" name="color[]" value="{{ $color->id }}" @foreach (old('color', $product->colors) as $col) @if (old('color') && $col == $color->id || !old('color') && $col->id == $color->id) checked @endif @endforeach >
                              <label class="form-check-label" for="color{{ $color->id }}">
                                 {{ $color->name }} <i class="fa-solid fa-circle text-{{ $color->name }}"></i></span>
                              </label>
                           </div>
                        </label>
                     @empty
                        <x-alert type="warning"><span class="small">Tidak ada varian warna!</span></x-alert>
                     @endforelse
                     @error('color')
                        <div class="small text-danger">{{ $message }}</div>
                     @enderror
                  </div>

                  <!-- input detail -->
                  <div class="col-12 mb-3">
                     <label for="detail" class="form-label">Detail Produk</label>
                     <input type="hidden" id="detail" name="detail" value="{{ old('detail', $product->detail) }}">
                     <trix-editor input="detail" class="@error('detail') is-invalid @enderror"></trix-editor>
                     @error('detail')
                        <div class="invalid-feedback">{{ $message }}</div>
                     @enderror
                  </div>

                  <!-- input foro -->
                  <div class="col-12">
                     <label for="image" class="form-label">Foto Produk</label>
                     <p class="small text-danger my-0">*kosongkan foto produk jika tidak ingin mengubahnya</p>
                     <div class="row">
                        <div class="col-12 col-sm-6 col-lg-4 drop-zone @error('image_1') is-invalid @enderror mx-2 mb-3">
                           <span class="drop-zone__prompt small">Drop file here or click to upload</span>
                           <input type="file" name="image_1" class="drop-zone__input" accept="image/jpg, image/jpeg, image/png">
                        </div>
                        <div class="col-12 col-sm-6 col-lg-4 drop-zone @error('image_2') is-invalid @enderror mx-2 mb-3">
                           <span class="drop-zone__prompt small">Drop file here or click to upload</span>
                           <input type="file" name="image_2" class="drop-zone__input" accept="image/jpg, image/jpeg, image/png">
                        </div>
                        <div class="col-12 col-sm-6 col-lg-4 drop-zone @error('image_3') is-invalid @enderror mx-2 mb-3">
                           <span class="drop-zone__prompt small">Drop file here or click to upload</span>
                           <input type="file" name="image_3" class="drop-zone__input" accept="image/jpg, image/jpeg, image/png">
                        </div>
                     </div>
                     @error('image_1')
                        <div class="small text-danger">{{ $message }}</div>
                     @enderror
                     @error('image_2')
                        <div class="small text-danger">{{ $message }}</div>
                     @enderror
                     @error('image_3')
                        <div class="small text-danger">{{ $message }}</div>
                     @enderror
                     <p class="small text-muted my-0">*dimensi foto produk 500x500 (png, jpg, jpeg)</p>
                     <p class="small text-muted my-0">*maksimal 1MB</p>
                  </div>
               </div>
               <x-button type="submit" class="btn-primary rounded px-3"><i class="fa-solid fa-save"></i> Simpan</x-button>
            </form>
         </div>
      </div>
   </div>
@endsection
@push('script')
   <script type="module" src="{{ asset('js/admin/product.js') }}"></script>
@endpush
