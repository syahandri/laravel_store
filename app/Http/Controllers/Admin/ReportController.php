<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\InvoiceSent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class ReportController extends Controller
{
   // view laporan penjualan
   public function sales(Request $request)
   {
      if ($request->ajax()) {
         $orders = InvoiceSent::with('products', 'user')->get();
         return DataTables::of($orders)
            ->addIndexColumn()
            ->addColumn('user', function (InvoiceSent $invoiceSent) {
               return $invoiceSent->user->name;
            })
            ->addColumn('product', function (InvoiceSent $invoiceSent) {
               return $invoiceSent->products->map(fn ($product) => $product->name)->implode(", <br>");
            })
            ->addColumn('price', function (InvoiceSent $invoiceSent) {
               return $invoiceSent->products->map(fn ($product) => 'Rp ' . number_format($product->pivot->price, "0", ",", "."))->implode(", <br>");
            })
            ->addColumn('quantity', function (InvoiceSent $invoiceSent) {
               return $invoiceSent->products->map(fn ($product) => $product->pivot->quantity)->implode(", <br>");
            })
            ->rawColumns(['user', 'product', 'price', 'quantity'])
            ->make(true);
      }

      return view('admin.report.sales', [
         'title' => 'Laporan Penjualan',
      ]);
   }
}
