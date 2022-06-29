<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Checkout;
use App\Models\InvoiceCancel;
use App\Models\InvoiceSent;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
   // view profil
   public function index()
   {
      $user_id = auth()->user()->id;
      return view('user.profile.index', [
         'title' => 'Profil',
         'user' => User::with('addresses')->find($user_id),
         'count_pending' => $this->count_invoice('Pending'),
         'count_confirmed' => $this->count_invoice('Confirmed'),
         'count_proccess' => $this->count_invoice('Proccess'),
         'count_send' => InvoiceSent::where('user_id', $user_id)->count(),
         'count_cancel' => InvoiceCancel::where('user_id', $user_id)->count(),
      ]);
   }

   // ambil data user
   public function show(User $user)
   {
      return ['code' => 200, 'result' => $user];
   }

   // update user
   public function update(Request $request, User $user)
   {
      $validator = Validator::make($request->all(), [
         'name' => 'required',
         'phone' => 'max:13',
         'image' => 'image|file|max:1024|mimetypes:image/jpg,image/jpeg,image/png'
      ]);

      if ($validator->fails()) {
         return ['code' => 422, 'invalid' => $validator->getMessageBag()];
      }

      try {
         $validated = $validator->validated();
         if ($request->file('image')) {
            // hapus foto lama jika ada
            if ($request->old_image && $request->old_image != 'profile/default.jpg') {
               Storage::delete($request->old_image);
            }

            // simpan foto profil yang diupload
            $validated['image'] = $request->file('image')->store('profile');
         }

         $validated['name'] = str()->headline($validated['name']);
         $user->update($validated);
         return ['code' => 200, 'message' => 'Data berhasil diubah.'];
      } catch (\Throwable $th) {
         return ['code' => 200, 'message' => 'Gagal mengubah data, silahkan coba lagi.'];
      }
   }

   private function count_invoice($status)
   {
      $user_id = auth()->user()->id;
      return Checkout::where(['user_id' => $user_id, 'status' => $status])->count();
   }
}
