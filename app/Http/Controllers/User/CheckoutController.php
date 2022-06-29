<?php

namespace App\Http\Controllers\User;

use Carbon\Carbon;
use App\Models\Checkout;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;

class CheckoutController extends Controller
{
   // Checkout
   public function index(Request $request, Checkout $checkout)
   {
      // invoice, tanggal pesan dan batas pembayaran
      $invoice = 'INV' . str()->random(10) . Carbon::now()->timestamp;
      $order_date = Carbon::now();
      $deadline_date = Carbon::create($order_date->year, $order_date->month, $order_date->day, 23, 59, 0)->addDay();

      // masukan invoice, tanggal pesan dan batas pembayaran ke $request
      $request['invoice'] = str()->upper($invoice);
      $request['order_date'] = $order_date;
      $request['deadline_date'] = $deadline_date;

      // cek stok produk sebelum checkout
      return $checkout->checkStock($request->all());
   }

   // get by invoice
   public function show(Checkout $checkout)
   {
      return ['code' => 200, 'result' => $checkout->load('products')];
   }
   // export invoice pdf
   public function export(Checkout $checkout)
   {
      $checkout->load('products');
      $pdf = PDF::loadView('user.checkout.export', [
         'data' => $checkout,
      ]);
      $pdf->setPaper('A3', 'landscape');
      return $pdf->download('invoice.pdf');
   }
}
