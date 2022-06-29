<?php

use App\Models\Product;
use App\Models\InvoiceSent;
use Illuminate\Support\Facades\DB;
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
      Schema::create('invoice_sent_product', function (Blueprint $table) {
         $table->foreignIdFor(InvoiceSent::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
         $table->foreignIdFor(Product::class)->constrained()->restrictOnUpdate()->restrictOnDelete();
         $table->string('color', 15);
         $table->string('size', 5);
         $table->integer('price');
         $table->integer('quantity');
         $table->integer('sub_total');
         $table->text('note')->nullable();
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
      Schema::dropIfExists('invoice_sent_product');
   }
};
