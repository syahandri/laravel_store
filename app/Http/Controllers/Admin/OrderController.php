<?php

namespace App\Http\Controllers\Admin;

use App\Models\Checkout;
use App\Models\InvoiceSent;
use Illuminate\Http\Request;
use App\Models\InvoiceCancel;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
   // view order pending
   public function pending(Request $request)
   {
      if ($request->ajax()) {
         $orders = Checkout::where('status', 'Pending')->get();
         return DataTables::of($orders)
            ->addIndexColumn()
            ->addColumn('opsi', function ($order) {
               return '
               <div class="d-flex align-items-center">
                  <button data-id="' . $order->id . '" type="button" class="btn btn-sm btn-primary btn-order-detail">
                     Detail
                  </button>
               </div>';
            })
            ->editColumn('status', '<span class="badge bg-warning px-2">{{ $status }}</span>')
            ->rawColumns(['status', 'opsi'])
            ->make(true);
      }

      return view('admin.order.pending', [
         'title' => 'Pesanan Pending',
      ]);
   }

   // view order konfirmasi
   public function confirm(Request $request)
   {
      if ($request->ajax()) {
         $orders = Checkout::with('confirm')->where('status', 'Confirmed')->get();
         return DataTables::of($orders)
            ->addIndexColumn()
            ->addColumn('opsi', function ($order) {
               return '
               <div class="d-flex align-items-center">
                  <button data-id="' . $order->id . '" type="button" class="btn btn-sm btn-primary btn-order-detail">
                     Detail
                  </button>
               </div>';
            })
            ->rawColumns(['opsi'])
            ->make(true);
      }

      return view('admin.order.confirm', [
         'title' => 'Pesanan Dikonfirmasi',
      ]);
   }

   // verify / proses pesanan
   public function verify(Request $request, Checkout $checkout)
   {
      try {
         $checkout->update(['status' => $request->status]);
         return ['code' => 200, 'message' => 'Verifikasi berhasil, pesanan diproses.'];
      } catch (\Throwable $th) {
         return ['code' => 500, 'message' => 'Gagal memproses pesanan, silahkan coba lagi.'];
      }
   }

   // tolak konfirmasi pembayaran
   public function deny(Request $request, Checkout $checkout)
   {
      return (new InvoiceCancel())->cancelInvoice($request->reason, $checkout->load('products', 'confirm'));
   }

   // view order proses
   public function proccess(Request $request)
   {
      if ($request->ajax()) {
         $orders = Checkout::where('status', 'Proccess')->get();
         return DataTables::of($orders)
            ->addIndexColumn()
            ->addColumn('opsi', function ($order) {
               return '
               <div class="d-flex align-items-center">
                  <button data-id="' . $order->id . '" type="button" class="btn btn-sm btn-primary btn-order-send">
                     Kirim
                  </button>
               </div>';
            })
            ->editColumn('cost', 'Rp. {{ number_format($cost, "0", ",", ".") }}')
            ->rawColumns(['opsi', 'cost'])
            ->make(true);
      }

      return view('admin.order.proccess', [
         'title' => 'Pesanan Diproses',
      ]);
   }

   // kirim pesanan
   public function send(Request $request, Checkout $checkout)
   {
      return (new InvoiceSent())->sent($request->resi, $checkout->load('products'));
   }

   // view order dikirim
   public function sent(Request $request)
   {
      if ($request->ajax()) {
         $orders = InvoiceSent::all();
         return DataTables::of($orders)
            ->addIndexColumn()
            ->addColumn('opsi', function ($order) {
               return '
               <div class="d-flex align-items-center">
                  <button data-id="' . $order->id . '" type="button" class="btn btn-sm btn-primary btn-order-detail">
                     Detail
                  </button>
               </div>';
            })
            ->editColumn('cost', 'Rp. {{ number_format($cost, "0", ",", ".") }}')
            ->rawColumns(['opsi', 'cost'])
            ->make(true);
      }

      return view('admin.order.sent', [
         'title' => 'Pesanan Dikirim',
      ]);
   }

   // view order dibatalkan
   public function cancel(Request $request)
   {
      if ($request->ajax()) {
         $orders = InvoiceCancel::all();
         return DataTables::of($orders)
            ->addIndexColumn()
            ->addColumn('opsi', function ($order) {
               return '
               <div class="d-flex align-items-center">
                  <button data-id="' . $order->id . '" type="button" class="btn btn-sm btn-primary btn-order-detail">
                     Detail
                  </button>
               </div>';
            })
            ->editColumn('cost', 'Rp. {{ number_format($cost, "0", ",", ".") }}')
            ->rawColumns(['opsi', 'cost'])
            ->make(true);
      }

      return view('admin.order.cancel', [
         'title' => 'Pesanan Dibatalkan',
      ]);
   }

   // get detail pesanan di tabel checkout
   public function detailOrder(Checkout $checkout)
   {
      if ($checkout->status == 'Confirmed') {
         $checkout = $checkout->load('products', 'user', 'confirm');
      } else {
         $checkout = $checkout->load('products', 'user');
      }

      return ['code' => 200, 'result' => $checkout];
   }

   // get detail pesanan di tabel pesanan terkirim
   public function detailSent(InvoiceSent $invoiceSent)
   {
      return ['code' => 200, 'result' => $invoiceSent->load('products', 'user')];
   }

   // get detail pesanan di tabel pesanan batal
   public function detailCancel(InvoiceCancel $invoiceCancel)
   {
      return ['code' => 200, 'result' => $invoiceCancel->load('products', 'user')];
   }
}
