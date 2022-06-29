<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Product;
use App\Models\Checkout;
use App\Notifications\InvoiceNotif;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InvoiceCancel extends Model
{
   use HasFactory;
   protected $guarded = ['id'];
   protected $dates = ['order_date'];

   // hapus invoice yang melewati deadline
   public function delInvoiceIsOverDeadline()
   {
      // ambil semua invoice yang melewati deadline
      $checkouts = Checkout::with('products', 'user')->where('status', 'Pending')->where('deadline_date', '<', Carbon::now())->get();
      if ($checkouts->count()) {
         foreach ($checkouts as $checkout) {
            // insert invoice ke tabel invoice cancel
            $this->create([
               'user_id'      => $checkout->user_id,
               'invoice'      => $checkout->invoice,
               'courier'      => $checkout->courier,
               'service'      => $checkout->service,
               'estimate'     => $checkout->estimate,
               'cost'         => $checkout->cost,
               'total'        => $checkout->total,
               'order_date'   => $checkout->order_date,
            ]);

            foreach ($checkout->products as $product) {
               // insert produk ke tabel cancel product
               $invoice_cancels = $this->where('invoice', $checkout->invoice)->get();
               foreach ($invoice_cancels as $cancel) {
                  $cancel->products()->attach($cancel->id, [
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

            // hapus invoice yang melewati deadline
            $checkout->destroy($checkout->id);

            // kirim email notifikasi ke user yang pesanananya melewati deadline
            $name = $checkout->user->name;
            $invoice = $checkout->invoice;
            $subject = 'Pesanan Dibatalkan';
            $message = 'telah dibatalkan karena melewati batas waktu pembayaran';

            $user = User::find($checkout->user_id);
            $user->notify(new InvoiceNotif($name, $invoice, $subject, $message, 'cancel'));
         }
      }
   }

   // batalkan invoice untuk konfirmasi pembayaran tidak valid
   public function cancelInvoice($reason, $checkout)
   {
      try {

         // kirim email notifikasi ke user
         $name = $checkout->user->name;
         $invoice = $checkout->invoice;
         $subject = 'Pesanan Dibatalkan';
         $message = "telah dibatalkan karena $reason";

         $user = User::find($checkout->user_id);
         $user->notify(new InvoiceNotif($name, $invoice, $subject, $message, 'cancel'));

         // insert invoice ke tabel invoice cancel
         $this->create([
            'user_id'      => $checkout->user_id,
            'invoice'      => $checkout->invoice,
            'courier'      => $checkout->courier,
            'service'      => $checkout->service,
            'estimate'     => $checkout->estimate,
            'cost'         => $checkout->cost,
            'total'        => $checkout->total,
            'reason'       => $reason,
            'order_date'   => $checkout->order_date,
         ]);

         foreach ($checkout->products as $product) {
            // insert produk ke tabel cancel product
            $invoice_cancels = $this->where('invoice', $checkout->invoice)->get();
            foreach ($invoice_cancels as $cancel) {
               $cancel->products()->attach($cancel->id, [
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

         // hapus konfimasi pembayaran
         Confirm::destroy($checkout->confirm->id);
         Storage::delete($checkout->confirm->image);

         // hapus invoice 
         $checkout->delete();

         return ['code' => 200, 'message' => 'Pesanan Dibatalkan.'];
      } catch (\Throwable $th) {
         return ['code' => 500, 'message' => 'Gagal membatalakan pesanan, silahkan coba lagi.'];
      }
   }

   protected function orderDate(): Attribute
   {
      return Attribute::make(
         get: fn($val) =>  Carbon::parse($val)->timezone('Asia/Jakarta')->toDateTimeString(),
      );
   }

   protected function createdAt(): Attribute
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
