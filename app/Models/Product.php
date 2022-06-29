<?php

namespace App\Models;

use App\Models\Cart;
use App\Models\Size;
use App\Models\Color;
use App\Models\Category;
use App\Models\Checkout;
use App\Models\InvoiceSent;
use App\Models\InvoiceCancel;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
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

   public function categories()
   {
      return $this->belongsToMany(Category::class);
   }

   public function colors()
   {
      return $this->belongsToMany(Color::class);
   }

   public function sizes()
   {
      return $this->belongsToMany(Size::class);
   }

   public function carts()
   {
      return $this->belongsToMany(Cart::class);
   }

   public function checkouts()
   {
      return $this->belongsToMany(Checkout::class);
   }

   public function invoiceCancels()
   {
      return $this->belongsToMany(InvoiceCancel::class);
   }

   public function invoiceSents()
   {
      return $this->belongsToMany(InvoiceSent::class);
   }
}
