<?php

namespace App\Models;

use App\Models\User;
use App\Models\Product;
use App\Notifications\InvoiceNotif;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InvoiceSent extends Model
{
   use HasFactory;
   protected $guarded = ['id'];
   protected $dates = ['order_date', 'sent_date'];

   // kirim pesanan
   public function sent($resi, $checkout)
   {
      try {
         // insert invoice ke tabel invoice sent
         $this->create([
            'user_id'      => $checkout->user_id,
            'invoice'      => $checkout->invoice,
            'resi'         => $resi,
            'courier'      => $checkout->courier,
            'service'      => $checkout->service,
            'estimate'     => $checkout->estimate,
            'cost'         => $checkout->cost,
            'total'        => $checkout->total,
            'address'      => $checkout->address,
            'sub_district' => $checkout->sub_district,
            'city'         => $checkout->city,
            'province'     => $checkout->province,
            'postal_code'  => $checkout->postal_code,
            'order_date'   => $checkout->order_date,
            'sent_date'    => Carbon::now(),
         ]);

         foreach ($checkout->products as $product) {
            // insert produk ke tabel sent product
            $invoice_sents = $this->where('invoice', $checkout->invoice)->get();
            foreach ($invoice_sents as $sent) {
               $sent->products()->attach($sent->id, [
                  'product_id' => $product->pivot->product_id,
                  'color' => $product->pivot->color,
                  'size' => $product->pivot->size,
                  'price' => $product->pivot->price,
                  'quantity' => $product->pivot->quantity,
                  'sub_total' => $product->pivot->sub_total,
                  'note' => $product->pivot->note
               ]);
            }
         }

         // hapus invoice 
         $checkout->delete();

         // kirim email notifikasi ke user
         $name = $checkout->user->name;
         $invoice = $checkout->invoice;
         $subject = 'Pesanan Dikirim';
         $message = 'telah dikirim. Nomor Resi: ' . str()->upper($resi);

         $user = User::find($checkout->user_id);
         $user->notify(new InvoiceNotif($name, $invoice, $subject, $message, 'sent'));

         return ['code' => 200, 'message' => 'Pesanan Dikirim.'];
      } catch (\Throwable $th) {
         return ['code' => 500, 'message' => 'Gagal mengirim pesanan, silahkan coba lagi.'];
      }
   }

   protected function resi(): Attribute
   {
      return Attribute::make(
         set: fn ($val) => str()->upper($val),
         get: fn ($val) => str()->upper($val),
      );
   }

   protected function orderDate(): Attribute
   {
      return Attribute::make(
         get: fn($val) =>  Carbon::parse($val)->timezone('Asia/Jakarta')->toDateTimeString(),
      );
   }

   protected function sentDate(): Attribute
   {
      return Attribute::make(
         get: fn($val) =>  Carbon::parse($val)->timezone('Asia/Jakarta')->toDateTimeString(),
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
}
