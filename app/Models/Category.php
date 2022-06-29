<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
   use HasFactory;
   protected $guarded = ['id'];

   protected function name(): Attribute
   {
      return Attribute::make(
         get: fn($val) => str()->headline($val),
         set: fn($val) => str()->headline($val),
      );
   }

   public function products()
   {
      return $this->belongsToMany(Product::class);
   }
}
