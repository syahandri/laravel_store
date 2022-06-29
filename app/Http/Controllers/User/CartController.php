<?php

namespace App\Http\Controllers\User;

use App\Models\Cart;
use App\Models\Address;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
   // View cart
   public function index()
   {
      $user_id = auth()->user()->id;
      return view('user.cart.index', [
         'title' => 'Keranjang',
         'carts' => Cart::where('user_id', $user_id)->with(['products' => fn ($q) => $q->orderBy('pivot_created_at', 'DESC')])->first(),
         'address' => Address::where(['user_id' => $user_id, 'flags' => 1])->first()
      ]);
   }

   // Tambah produk ke keranjang
   public function add(Request $request, Cart $cart)
   {
      // Validasi input
      $validator = Validator::make($request->all(), [
         'product_id' => 'required',
         'color' => 'required',
         'size' => 'required',
         'quantity' => 'required|integer|min:1'
      ]);

      if ($validator->fails()) {
         return ['code' => 422, 'invalid' => $validator->getMessageBag()];
      }

      return $cart->add($validator->validated());
   }

   // Hapus produk dalam keranjang
   public function destroy(Request $request, Cart $cart)
   {
      $product = [
         'product_id' => $request->product_id,
         'color' => $request->color,
         'size' => $request->size,
      ];

      return $cart->deleteItem($product);
   }

   // Hitung produk dalam keranjang
   public function count()
   {
      $user_id = auth()->user()->id;
      $cart = Cart::where('user_id', $user_id)->first();
      $count = $cart ? $cart->products->count() : 0;
      return ['code' => 200, 'count' => $count < 100 ? $count : '99+'];
   }

   // Cek ongkir dari API raja ongkir
   public function getCost(Request $request)
   {
      $key = env('API_KEY');
      $uri = env('URI');
      $origin = 92; // brebes

      $res = Http::withHeaders([
         'key' => $key
      ])->timeout(30)->post($uri . '/cost', [
         'origin' => $origin,
         'destination' => $request->destination,
         'weight' => $request->weight,
         'courier' => $request->courier,
      ]);

      if ($res->successful()) {
         return ['code' => $res['rajaongkir']['status']['code'], 'result' => $res['rajaongkir']['results']];
      }

      if ($res->failed()) {
         return ['code' => $res['rajaongkir']['status']['code']];
      }
   }
}
