<?php

namespace App\Models;

use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cart extends Model
{
   use HasFactory;
   protected $guarded = ['id'];

   public function add($product)
   {
      try {
         $user_id = auth()->user()->id;

         // Update jika user sudah ada produk di keranjang, insert jika belum ada
         $this->updateOrCreate(
            ['user_id' => $user_id],
            ['user_id' => $user_id, 'updated_at' => now()]
         );

         //   Ambil produk di keranjang user
         $cart = $this->where('user_id', $user_id)->first();
         $cart_product = DB::table('cart_product')->where([
            'cart_id' => $cart->id,
            'product_id' => $product['product_id'],
            'color' => $product['color'],
            'size' => $product['size'],
         ]);

         // Tambah quantity jika di keranjang user sudah ada varian produk yang sama, insert jika belum ada
         $data_product = $cart_product->first();
         if ($data_product) {
            $cart_product->increment('quantity', $product['quantity']);
         } else {
            $cart->products()->attach($cart->id, $product);
         }

         return ['code' => 200, 'message' => 'Produk berhasil ditambahkan ke keranjang.'];
      } catch (\Throwable $th) {
         return ['code' => 500, 'message' => 'Produk gagal ditambahkan ke keranjang, silahkan coba lagi!'];
      }
   }

   public function deleteItem($product)
   {
      try {
         $user_id = auth()->user()->id;
         $cart = $this->where('user_id', $user_id)->first();

         // Hapus produk dalam keranjang
         DB::table('cart_product')->where([
            'cart_id' => $cart->id,
            'product_id' => $product['product_id'],
            'color' => $product['color'],
            'size' => $product['size'],
         ])->delete();

         // Hapus keranjang jika produk tidak ada
         $count = $cart->products->count();
         if ($count < 1) {
            $cart->destroy($cart->id);
         }

         return ['code' => 200, 'message' => 'Produk berhasil dihapus dari keranjang.'];
      } catch (\Throwable $th) {
         return ['code' => 500, 'message' => 'Produk gagal dihapus dari keranjang, silahkan coba lagi!'];
      }
   }

   public function user()
   {
      return $this->belongsTo(User::class);
   }

   public function products()
   {
      return $this->belongsToMany(Product::class)->withPivot('quantity', 'color', 'size', 'created_at', 'updated_at');
   }
}
