<?php

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
   /**
    * Run the migrations.
    *
    * @return void
    */
   public function up()
   {
      Schema::create('cart_product', function (Blueprint $table) {
         $table->foreignIdFor(Cart::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
         $table->foreignIdFor(Product::class)->constrained()->restrictOnUpdate()->restrictOnDelete();
         $table->integer('quantity');
         $table->string('color', 15);
         $table->string('size', 5);
         $table->timestamps();
      });
   }

   /**
    * Reverse the migrations.
    *
    * @return void
    */
   public function down()
   {
      Schema::dropIfExists('cart_product');
   }
};
