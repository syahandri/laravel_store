<?php

namespace App\Models;

use App\Models\Cart;
use App\Models\Role;
use App\Models\Confirm;
use App\Models\Checkout;
use App\Models\InvoiceSent;
use App\Models\InvoiceCancel;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
   use HasApiTokens, HasFactory, Notifiable;

   /**
    * The attributes that are mass assignable.
    *
    * @var array<int, string>
    */
   protected $guarded = ['id'];
   /**
    * The attributes that should be hidden for serialization.
    *
    * @var array<int, string>
    */
   protected $hidden = [
      'password',
      'remember_token',
   ];

   /**
    * The attributes that should be cast.
    *
    * @var array<string, string>
    */
   protected $casts = [
      'email_verified_at' => 'datetime',
   ];

   public function cart()
   {
      return $this->hasOne(Cart::class);
   }

   public function checkouts()
   {
      return $this->hasMany(Checkout::class);
   }

   public function invoiceCancels()
   {
      return $this->hasMany(InvoiceCancel::class);
   }

   public function invoiceSents()
   {
      return $this->hasMany(InvoiceSent::class);
   }

   public function confirms()
   {
      return $this->hasMany(Confirm::class);
   }

   public function role()
   {
      return $this->belongsTo(Role::class);
   }

   public function addresses()
   {
      return $this->hasMany(Address::class);
   }
}
