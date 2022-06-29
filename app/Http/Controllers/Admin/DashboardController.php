<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Checkout;
use App\Models\InvoiceCancel;
use App\Models\InvoiceSent;

class DashboardController extends Controller
{
   // view dashboard
   public function index()
   {
      return view('admin.dashboard.index', [
         'title' => 'Dashboard',
         'product_count' => Product::count(),
         'category_count' => Category::count(),
         'sent_count' => InvoiceSent::count(),
         'cancel_count' => InvoiceCancel::count(),
         'pending_count' => Checkout::where('status', 'Pending')->count(),
         'confirmed_count' => Checkout::where('status', 'confirmed')->count(),
         'proccess_count' => Checkout::where('status', 'Proccess')->count(),
         'bestseller' => Product::orderBy('sold', 'desc')->take(15)->get()
      ]);
   }
}
