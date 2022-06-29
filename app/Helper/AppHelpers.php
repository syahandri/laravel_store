<?php

namespace App\Helper;

use Illuminate\Support\Facades\Http;

class AppHelpers
{
   static function getProvinceOrCity($url)
   {
      $key = env('API_KEY');
      $uri = env('URI');

      $res = Http::withHeaders([
         'key' => $key
      ])->timeout(30)->get($uri . $url);

      if ($res->successful()) {
         return ['code' => $res['rajaongkir']['status']['code'], 'result' => $res['rajaongkir']['results']];
      }

      if ($res->failed()) {
         return ['code' => $res['rajaongkir']['status']['code']];
      }
   }
}
