<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\User\OrderController;
use App\Http\Controllers\User\AddressController;
use App\Http\Controllers\User\ConfirmController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\User\CheckoutController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ReportController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [HomeController::class, 'index']);

Route::prefix('product')->controller(HomeController::class)->group(function () {
   Route::get('/', 'product_request');
   Route::get('category/{slug}', 'product_category');
   Route::get('detail/{product:slug}', 'product_detail');
   Route::get('find', 'search_product');
});

Route::middleware('verified')->group(function () {
   Route::prefix('cart')->controller(CartController::class)->group(function () {
      Route::get('/', 'index');
      Route::get('count', 'count');
      Route::post('cost', 'getCost');
      Route::delete('remove', 'destroy');
      Route::post('add', 'add');
   });

   Route::prefix('checkout')->controller(CheckoutController::class)->group(function () {
      Route::post('/', 'index');
      Route::get('{checkout}', 'show');
      Route::get('export/{checkout}', 'export');
   });

   Route::prefix('payment-confirm')->controller(ConfirmController::class)->group(function () {
      Route::get('/', 'index');
      Route::post('confirm', 'confirm');
   });

   Route::prefix('profile')->controller(ProfileController::class)->group(function () {
      Route::get('/', 'index');
      Route::get('{user}', 'show');
      Route::put('{user}', 'update');
   });

   Route::controller(AddressController::class)->group(function () {
      Route::get('address/{address}', 'show');
      Route::post('address', 'store');
      Route::put('address/{address}', 'update');
      Route::get('province', 'getProvince');
      Route::get('city', 'getCity');
   });

   Route::prefix('orders/status')->controller(OrderController::class)->group(function () {
      Route::get('pending', 'pending');
      Route::get('confirmed', 'confirmed');
      Route::get('proccess', 'proccess');
      Route::get('sent', 'sent');
      Route::get('cancel', 'cancel');
   });
});

Route::middleware(['auth_admin', 'verified', 'admin'])->group(function () {
   Route::prefix('store-admin')->group(function () {
      Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
      
      Route::resource('product', ProductController::class)->scoped(['product' => 'id_product']);
      Route::resource('category', CategoryController::class)->except('create', 'edit')->scoped(['category' => 'slug']);

      Route::prefix('order')->controller(AdminOrderController::class)->group(function () {
         Route::get('pending', 'pending')->name('order.pending');
         Route::get('confirm', 'confirm')->name('order.confirm');
         Route::get('proccess', 'proccess')->name('order.proccess');
         Route::get('sent', 'sent')->name('order.sent');
         Route::get('cancel', 'cancel')->name('order.cancel');
         Route::post('verify/{checkout}', 'verify')->name('order.verify');
         Route::post('deny/{checkout}', 'deny')->name('order.deny');
         Route::post('send/{checkout}', 'send')->name('order.send');
         Route::get('detail/{checkout}', 'detailOrder')->name('detail.order');
         Route::get('detail-sent/{invoiceSent}', 'detailSent')->name('detail.sent');
         Route::get('detail-cancel/{invoiceCancel}', 'detailCancel')->name('detail.cancel');
      });

      Route::get('report/sales', [ReportController::class, 'sales'])->name('report.sales');
   });
});

require __DIR__ . '/auth.php';
