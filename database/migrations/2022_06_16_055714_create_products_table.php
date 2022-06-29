<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   /**
    * Run the migrations.
    *
    * @return void
    */
   public function up()
   {
      Schema::create('products', function (Blueprint $table) {
         $table->id();
         $table->string('id_product')->unique();
         $table->string('slug', 200)->unique();
         $table->string('name', 150)->unique();
         $table->text('detail')->fulltext();
         $table->integer('price', false, true);
         $table->float('discount', 8, 2, true)->default(0);
         $table->integer('weight', false, true);
         $table->integer('stock', false, true);
         $table->string('image_1', 150)->default('product/default.jpg');
         $table->string('image_2', 150)->default('product/default.jpg');
         $table->string('image_3', 150)->default('product/default.jpg');
         $table->integer('sold', false, true)->default(0);

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
      Schema::dropIfExists('products');
   }
};
