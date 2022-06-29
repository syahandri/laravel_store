<?php

namespace App\Http\Controllers\User;

use App\Models\Address;
use App\Helper\AppHelpers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AddressController extends Controller
{
   // ambil data alamat
   public function show(Address $address)
   {
      return ['code' => 200, 'result' => $address];
   }

   // insert alamat
   public function store(Request $request)
   {
      $address = new Address();
      $user_id = auth()->user()->id;

      $validator = Validator::make($request->all(), [
         'address' => 'required',
         'city' => 'required',
         'province' => 'required',
         'sub_district' => 'required',
         'postal_code' => 'required'
      ]);

      if ($validator->fails()) {
         return ['code' => 422, 'invalid' => $validator->getMessageBag()];
      }

      try {
         $validated = $validator->validated();
         $validated['user_id'] = $user_id;
         $validated['city_id'] = $request->city_id;
         $validated['province_id'] = $request->province_id;

         if ($request->flags) {
            $validated['flags'] = $request->flags;
            $address->where('user_id', $user_id)->update(['flags' => 0]);
         }

         $validated = collect($validated)->map(fn ($val) => str()->headline($val));
         $address->create($validated->toArray());

         return ['code' => 200, 'message' => 'Alamat berhasil ditambahkan'];
      } catch (\Throwable $th) {
         return ['code' => 500, 'message' => 'Gagal menambahkan alamat, silahkan coba lagi.'];
      }
   }

   public function update(Request $request, Address $address)
   {
      $user_id = auth()->user()->id;
      $validator = Validator::make($request->all(), [
         'address' => 'required',
         'city' => 'required',
         'province' => 'required',
         'sub_district' => 'required',
         'postal_code' => 'required'
      ]);

      if ($validator->fails()) {
         return ['code' => 422, 'invalid' => $validator->getMessageBag()];
      }

      try {
         $validated = $validator->validated();
         $validated['user_id'] = $user_id;
         $validated['city_id'] = $request->city_id;
         $validated['province_id'] = $request->province_id;

         if ($request->flags) {
            $address->where('user_id', $user_id)->update(['flags' => 0]);
         }

         $validated = collect($validated)->map(fn ($val) => str()->headline($val));
         $validated['flags'] = $request->flags ?? 0;
         $address->update($validated->toArray());

         return ['code' => 200, 'message' => 'Alamat berhasil diuabh'];
      } catch (\Throwable $th) {
         return ['code' => 500, 'message' => 'Gagal mengubah alamat, silahkan coba lagi.'];
      }
   }

   // ambil provinsi dari API raja ongkir
   public function getProvince()
   {
      $province = '/province';
      return AppHelpers::getProvinceOrCity($province);
   }

   // ambil kabupaten / kota dari provinsi
   public function getCity(Request $request)
   {
      $city = "/city?province=$request->province";
      return AppHelpers::getProvinceOrCity($city);
   }
}
