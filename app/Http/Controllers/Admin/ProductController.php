<?php

namespace App\Http\Controllers\Admin;

use App\Models\Size;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Color;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
   /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function index(Request $request)
   {
      if ($request->ajax()) {
         $products = Product::latest();
         return DataTables::of($products)
            ->addIndexColumn()
            ->addColumn('opsi', function ($product) {
               return '
               <div class="d-flex align-items-center">

                  <button data-id="' . $product->id_product . '" type="button" class="btn btn-sm border-0 btn-product-detail">
                     <i class="fa-solid fa-eye text-primary"></i>
                  </button>

                  <a href="' . route("product.edit", $product->id_product) . '" class="btn btn-sm border-0">
                     <i class="fa-solid fa-pencil text-warning"></i>
                  </a>

                  <button data-token="' . csrf_token() . '" data-id="' . $product->id_product . '" type="button" class="btn border-0 btn-sm btn-delete-product">
                     <i class="fa-solid fa-trash text-danger"></i>
                  </button>

               </div>
               ';
            })
            ->editColumn('price', 'Rp. {{ number_format($price, "0", ",", ".")}}')
            ->editColumn('discount', '{{ number_format($discount, "0", ",", ".")}}%')
            ->editColumn('stock', '{{ number_format($stock, "0", ",", ".")}}')
            ->editColumn('weight', '{{ number_format($weight, "0", ",", ".")}} Gram')
            ->editColumn('sold', '{{ number_format($sold, "0", ",", ".")}}')
            ->rawColumns(['opsi'])
            ->make(true);
      }

      return view('admin.product.index', [
         'title' => 'Kelola Produk'
      ]);
   }

   /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function create()
   {
      return view('admin.product.add', [
         'title' => 'Tambah Produk',
         'categories' => Category::all(),
         'colors' => Color::all(),
         'sizes' => Size::all(),
      ]);
   }

   /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
   public function store(Request $request)
   {
      $validated = $request->validate([
         'name' => 'required|unique:products',
         'price' => 'required|numeric|min:1',
         'weight' => 'required|numeric|min:1',
         'stock' => 'required|numeric|min:1',
         'discount' => 'required|numeric|max:100',
         'detail' => 'required',
         'image_1' => 'required|image|max:1024|mimetypes:image/jpg,image/jpeg,image/png',
         'image_2' => 'required|image|max:1024|mimetypes:image/jpg,image/jpeg,image/png',
         'image_3' => 'required|image|max:1024|mimetypes:image/jpg,image/jpeg,image/png',
         'category' => 'required',
         'size' => 'required',
         'color' => 'required'
      ]);

      // generate id product
      $validated['id_product'] = 'PRD' . Carbon::now()->timestamp;

      // generate slug
      $validated['slug'] = str()->slug($validated['name']);

      // simpan foto produk
      $validated['image_1'] = $request->file('image_1')->store('product');
      $validated['image_2'] = $request->file('image_2')->store('product');
      $validated['image_3'] = $request->file('image_3')->store('product');

      try {
         // simpan ke tabel produk
         Product::create($validated);

         // attach kategori produk
         $product = Product::where('id_product', $validated['id_product'])->first();
         foreach ($validated['category'] as $category) {
            $product->categories()->attach($product->id, ['category_id' => $category]);
         }

         // attach ukuran produk
         foreach ($validated['size'] as $size) {
            $product->sizes()->attach($product->id, ['size_id' => $size]);
         }

         // attach warna produk
         foreach ($validated['color'] as $color) {
            $product->colors()->attach($product->id, ['color_id' => $color]);
         }

         return redirect(route('product.index'))->with('message', 'Produk berhasil ditambahkan.');
      } catch (\Throwable $th) {
         return redirect(route('product.index'))->with('message', 'Produk gagal ditambahkan, silahkan coba lagi.');
      }
   }

   /**
    * Display the specified resource.
    *
    * @param  \App\Models\Product  $product
    * @return \Illuminate\Http\Response
    */
   public function show(Product $product)
   {
      return ['code' => 200, 'result' => $product->load('categories', 'colors', 'sizes')];
   }

   /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Models\Product  $product
    * @return \Illuminate\Http\Response
    */
   public function edit(Product $product)
   {
      return view('admin.product.edit', [
         'title' => 'Edit Produk',
         'product' => $product->load('categories', 'sizes', 'colors'),
         'categories' => Category::all(),
         'colors' => Color::all(),
         'sizes' => Size::all(),
      ]);
   }

   /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Models\Product  $product
    * @return \Illuminate\Http\Response
    */
   public function update(Request $request, Product $product)
   {
      $validated = $request->validate([
         'name' => 'required|unique:products,name,' . $product->id,
         'price' => 'required|numeric|min:1',
         'weight' => 'required|numeric|min:1',
         'stock' => 'required|numeric|min:1',
         'discount' => 'required|numeric|max:100',
         'detail' => 'required',
         'image_1' => 'image|max:1024|mimetypes:image/jpg,image/jpeg,image/png',
         'image_2' => 'image|max:1024|mimetypes:image/jpg,image/jpeg,image/png',
         'image_3' => 'image|max:1024|mimetypes:image/jpg,image/jpeg,image/png',
         'category' => 'required',
         'size' => 'required',
         'color' => 'required'
      ]);

      // generate slug
      $validated['slug'] = str()->slug($validated['name']);

      // cek apakah user mengupload foto produk
      if ($request->file('image_1')) {
         // hapus foto produk lama
         Storage::delete($request->old_image_1);
         // simpan foto produk
         $validated['image_1'] = $request->file('image_1')->store('product');
      }

      if ($request->file('image_2')) {
         Storage::delete($request->old_image_2);
         $validated['image_2'] = $request->file('image_2')->store('product');
      }

      if ($request->file('image_3')) {
         Storage::delete($request->old_image_3);
         $validated['image_3'] = $request->file('image_3')->store('product');
      }

      try {
         // update tabel produk
         $product->update($validated);

         // attach kategori produk
         $product->categories()->detach();
         foreach ($validated['category'] as $category) {
            $product->categories()->attach($product->id, ['category_id' => $category]);
         }

         // attach ukuran produk
         $product->sizes()->detach();
         foreach ($validated['size'] as $size) {
            $product->sizes()->attach($product->id, ['size_id' => $size]);
         }

         // attach warna produk
         $product->colors()->detach();
         foreach ($validated['color'] as $color) {
            $product->colors()->attach($product->id, ['color_id' => $color]);
         }

         return redirect(route('product.index'))->with('message', 'Produk berhasil diubah.');
      } catch (\Throwable $th) {
         return redirect(route('product.index'))->with('message', 'Produk gagal diubah, Silahkan coba lagi.');
      }
   }

   /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Models\Product  $product
    * @return \Illuminate\Http\Response
    */
   public function destroy(Product $product)
   {
      try {
         $product->delete();
         Storage::delete([$product->image_1, $product->image_2, $product->image_3]);
         return ['code' => 200, 'message' => 'Produk berhasil dihapus'];
      } catch (\Throwable $th) {
         return ['code' => 500, 'message' => 'Produk gagal dihapus, pastikan produk tidak ada di dalam pesanan user.'];
      }
   }
}
