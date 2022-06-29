<?php

use App\Models\Checkout;
use App\Models\User;
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
      Schema::create('confirms', function (Blueprint $table) {
         $table->id();
         $table->foreignIdFor(User::class);
         $table->foreignIdFor(Checkout::class);
         $table->string('bank', 15);
         $table->string('name', 50);
         $table->string('image', 150);
         $table->timestamp('confirm_date')->nullable();
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
      Schema::dropIfExists('confirms');
   }
};
