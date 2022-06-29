<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Checkout;
use App\Models\InvoiceCancel;
use App\Models\InvoiceSent;
use Illuminate\Http\Request;

class OrderController extends Controller
{
   // view invoice status pending / belum bayar
   public function pending()
   {
      return view('user.order-status.pending', [
         'title' => 'Pesanan Pending ',
         'orders' => $this->statusOrder('Pending')
      ]);
   }

   // view invoice status confirmed
   public function confirmed()
   {
      $user_id = auth()->user()->id;
      return view('user.order-status.confirmed', [
         'title' => 'Pesanan Dikonfirmasi',
         'orders' => Checkout::with('confirm', 'products')->where('status', 'Confirmed')->latest()->paginate(5)
      ]);
   }

   // view invoice status proccess
   public function proccess()
   {
      return view('user.order-status.proccess', [
         'title' => 'Pesanan Diproses ',
         'orders' => $this->statusOrder('Proccess')
      ]);
   }

   // view invoice status sent
   public function sent()
   {
      $user_id = auth()->user()->id;
      return view('user.order-status.sent', [
         'title' => 'Pesanan Dikirim',
         'orders' => InvoiceSent::with('products')->where('user_id', $user_id)->latest()->paginate(5)
      ]);
   }

   // view invoice status cancel
   public function cancel()
   {
      $user_id = auth()->user()->id;
      return view('user.order-status.cancel', [
         'title' => 'Pesanan Dibatalkan',
         'orders' => InvoiceCancel::with('products')->where('user_id', $user_id)->latest()->paginate(5)
      ]);
   }

   // get invoice by status
   private function statusOrder($status)
   {
      $user_id = auth()->user()->id;
      return Checkout::with('products')->where(['user_id' => $user_id, 'status' => $status])->latest()->paginate(5);
   }
}
