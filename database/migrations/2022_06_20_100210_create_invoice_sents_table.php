<?php

use App\Models\User;
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
      Schema::create('invoice_sents', function (Blueprint $table) {
         $table->id();
         $table->foreignIdFor(User::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
         $table->string('invoice', 50);
         $table->string('resi', 50)->nullable();
         $table->string('courier', 5);
         $table->string('service', 30);
         $table->string('estimate', 10);
         $table->integer('cost');
         $table->bigInteger('total');
         $table->string('address', 100);
         $table->string('sub_district', 50);
         $table->string('city', 50);
         $table->string('province', 50);
         $table->string('postal_code', 10);
         $table->timestamp('order_date')->nullable();
         $table->timestamp('sent_date')->nullable();
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
      Schema::dropIfExists('invoice_sents');
   }
};
