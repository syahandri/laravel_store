<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Confirm extends Model
{
   use HasFactory;
   protected $guarded = ['id'];
   protected $dates = ['confirm_date'];

   protected function name(): Attribute
   {
      return Attribute::make(
         set: fn ($val)  => str()->headline($val),
         get: fn ($val)  => str()->headline($val),
      );
   }

   protected function confirmDate(): Attribute
   {
      return Attribute::make(
         get: fn ($val) =>  Carbon::parse($val)->timezone('Asia/Jakarta')->toDateTimeString(),
      );
   }

   public function user()
   {
      return $this->belongsTo(User::class);
   }

   public function checkout()
   {
      return $this->belongsTo(Checkout::class);
   }
}
