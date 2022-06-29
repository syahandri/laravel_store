<?php

namespace App\Models;

use App\Models\Cart;
use App\Models\User;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Checkout extends Model
{
   use HasFactory;
   protected $guarded = ['id'];
   protected $dates = ['order_date', 'deadline_date'];


   public function checkStock($request)
   {
      try {
         // ambil produk dalam keranjang yang akan dicheckout
         $user_id = auth()->user()->id;
         $cart = Cart::where('user_id', $user_id)->with(['products' => function ($q) use ($request) {
            $q->whereIn('product_id', $request['product_id'])
               ->whereIn('color', $request['color'])
               ->whereIn('size', $request['size'])
               ->orderBy('pivot_created_at', 'DESC');
         }])->first();

         // cek stok produk
         foreach ($cart->products as $i => $product) {
            if ($product->stock < $request['quantity'][$i] && $product->stock > 0) {
               return ['code' => 400, 'product_id' => $product->id, 'stock' => $product->stock, 'message' => 'Stok tidak cukup'];
            }

            if ($product->stock == 0) {
               return ['code' => 400, 'product_id' => $product->id, 'stock' => $product->stock, 'message' => 'Stok sudah habis'];
            }
         }

         // lakukan checkout
         return $this->checkout($request, $cart->id);
      } catch (\Throwable $th) {
         return ['code' => 500, 'message' => 'Gagal melakukan checkout, silahkan coba lagi.'];
      }
   }

   private function checkout($request, $cart_id)
   {
      // simpan ke tabel checkout
      $user_id = auth()->user()->id;
      $this->create([
         'invoice'         => $request['invoice'],
         'user_id'         => $user_id,
         'courier'         => $request['courier'],
         'service'         => $request['service'],
         'estimate'        => $request['etd'],
         'cost'            => $request['cost'],
         'total'           => $request['total'],
         'address'         => $request['address'],
         'sub_district'    => $request['sub_district'],
         'city'            => $request['city'],
         'province'        => $request['province'],
         'postal_code'     => $request['postal_code'],
         'order_date'      => $request['order_date'],
         'deadline_date'   => $request['deadline_date'],
      ]);

      // simpan produk ke tabel produk checkout
      $checkout = $this->where('invoice', $request['invoice'])->first();
      foreach ($request['product_id'] as $i => $product_id) {
         $checkout->products()->attach($checkout->id, [
            'product_id' => $product_id,
            'color' => $request['color'][$i],
            'size' => $request['size'][$i],
            'price' => $request['price'][$i],
            'quantity' => $request['quantity'][$i],
            'sub_total' => $request['sub_total'][$i],
            'note' => $request['note'][$i],
         ]);

         // hapus produk dalam keranjang yang sudah dicheckout
         DB::table('cart_product')->where([
            'cart_id' => $cart_id,
            'product_id' => $product_id,
            'color' => $request['color'][$i],
            'size' => $request['size'][$i],
         ])->delete();
      }

      // hapus keranjang jika produk tidak ada
      $cart = Cart::find($cart_id);
      $count = $cart->products->count();
      if ($count == 0) {
         $cart->destroy($cart_id);
      }

      // kirim detail invoice yang baru dicheckout
      $invoice = $this->with('products')->where('invoice', $request['invoice'])->first();
      return ['code' => 200, 'result' => $invoice];
   }

   protected function orderDate(): Attribute
   {
      return Attribute::make(
         get: fn($val) =>  Carbon::parse($val)->timezone('Asia/Jakarta')->toDateTimeString(),
      );
   }

   protected function deadlineDate(): Attribute
   {
      return Attribute::make(
         get: fn($val) => $val ? Carbon::parse($val)->timezone('Asia/Jakarta')->toDateTimeString() : $val,
      );
   }

   protected function courier(): Attribute
   {
      return Attribute::make(
         get: fn ($val) => str()->upper($val),
      );
   }

   public function user()
   {
      return $this->belongsTo(User::class);
   }

   public function products()
   {
      return $this->belongsToMany(Product::class)->withPivot('color', 'size', 'price', 'quantity', 'sub_total', 'note', 'created_at', 'updated_at');
   }

   public function confirm()
   {
      return $this->HasOne(Confirm::class);
   }
}
