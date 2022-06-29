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
      Schema::create('invoice_cancels', function (Blueprint $table) {
         $table->id();
         $table->foreignIdFor(User::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
         $table->string('invoice', 50);
         $table->string('courier', 5);
         $table->string('service', 30);
         $table->string('estimate', 10);
         $table->integer('cost');
         $table->bigInteger('total');
         $table->string('reason')->default('Melewati batas waktu pembayaran');
         $table->timestamp('order_date')->nullable();
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
      Schema::dropIfExists('invoice_cancels');
   }
};
