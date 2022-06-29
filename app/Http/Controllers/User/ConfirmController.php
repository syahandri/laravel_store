<?php

namespace App\Http\Controllers\User;

use App\Models\Checkout;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Confirm;
use App\Models\User;
use App\Notifications\ConfirmNotif;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class ConfirmController extends Controller
{
   // View konfirmasi pembayaran
   public function index()
   {
      $user_id = auth()->user()->id;
      return view('user.payment-confirm.index', [
         'title' => 'Konfirmasi Pembayaran',
         'checkouts' => Checkout::where(['status' => 'Pending', 'user_id' => $user_id])->get(),
      ]);
   }

   // Konfirmasi pesanan
   public function confirm(Request $request)
   {
      $validator = Validator::make($request->all(), [
         'invoice' => 'required',
         'bank' => 'required',
         'name' => 'required',
         'image' => 'required|image|file|max:1024|mimetypes:image/jpg,image/jpeg,image/png'
      ]);

      if ($validator->fails()) {
         return ['code' => 422, 'invalid' => $validator->getMessageBag()];
      }

      try {
         $user_id = auth()->user()->id;

         $validated = $validator->validated();
         $validated['user_id'] = $user_id;
         $validated['checkout_id'] = $validated['invoice'];
         $validated['image'] = $request->file('image')->storeAs('confirm', "Bukti Transfer_$request->inv." . $request->image->extension());
         $validated['confirm_date'] = Carbon::now();

         // simpan konfirmasi
         Confirm::create($validated);
         // update status checkout
         Checkout::where('id', $validated['checkout_id'])->update(['deadline_date' => null, 'status' => 'Confirmed']);

         // kirim notif ke admin
         $user = User::find($user_id);
         $user_fullname = $user->name;
         $invoice = $request->inv;
         $name = str()->upper($validated['name']);
         $bank = $validated['bank'];
         $attach = $validated['image'];

         $admin = User::where('role_id', 1)->first();
         $admin->notify(new ConfirmNotif($user_fullname, $invoice, $name, $bank, $attach));

         return ['code' => 200, 'message' => 'Konfirmasi berhasil, mohon tunggu sampai kami memproses pesanan anda.'];
      } catch (\Throwable $th) {
         return ['code' => 500, 'message' => 'Gagal melakukan konfirmasi, silahkan coba lagi.'];
      }
   }
}
