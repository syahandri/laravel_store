<?php

namespace App\Http\Controllers\User;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;

class HomeController extends Controller
{
   // View home
   public function index()
   {
      return view('guest.home.index', [
         'title' => 'Home',
         'slider' => Product::latest()->take(5)->get(),
         'latest' => $this->orderBy('created_at', 'desc'),
         'best_seller' => $this->orderBy('sold', 'desc'),
         'cheap' => $this->orderBy('price', 'asc'),
      ]);
   }

   // View semua produk berdasarkan request
   public function product_request(Request $request)
   {
      if ($request->q === 'terbaru') {
         $products = Product::latest();
      } else if ($request->q === 'terlaris') {
         $products = Product::orderBy('sold', 'desc');
      } elseif ($request->q === 'termurah') {
         $products = Product::orderBy('price');
      } else {
         abort(404);
      }

      return view('guest.product.index', [
         'title' => 'Semua Produk ',
         'products' => $products->with('categories')->paginate(36)
      ]);
   }

   // View detail produk
   public function product_detail(Product $product)
   {
      /**
       * Isi array category_name dengan nama kategori dari produk yang dipilih
       * Cari produk yang berbeda dan memiliki kategori yang sama dengan produk yang dipilih
       */
      $category_name = [];
      foreach ($product->categories as $category) {
         array_push($category_name, $category->name);
      }

      $related_products = Product::with('categories')->whereNot(fn ($query) => $query->where('name', $product->name))
         ->whereHas('categories', fn ($query) => $query->whereIn('name', $category_name))->take(24)->get();

      return view('guest.product.detail', [
         'title' => 'Detail Produk',
         'product' => $product,
         'related_products' => $related_products
      ]);
   }

   // View cari produk
   public function search_product(Request $request)
   {
      $validated = $request->validate([
         's' => 'required'
      ]);

      return view('guest.product.index', [
         'title' => 'Hasil Pencarian ' . $validated['s'],
         'products' => Product::where('name', 'like', '%' . $validated['s'] . '%')->with('categories')
            ->orWhere('price', 'like', '%' . $validated['s'] . '%')
            ->orWhereHas('categories', fn ($query) => $query->where('name', 'like', '%' . $validated['s'] . '%'))->paginate(36)->withQueryString()
      ]);
   }

   // Get products by category
   public function product_category($category)
   {
      return view('guest.product.index', [
         'title' => "Produk Dalam $category",
         'products' => Product::with('categories')->whereHas('categories', fn ($query) => $query->where('slug', $category))->paginate(36)
      ]);
   }

   // Get product by order and sort
   private function orderBy($order, $sort)
   {
      return Product::with('categories')->orderBy($order, $sort)->take(20)->get();
   }
}
