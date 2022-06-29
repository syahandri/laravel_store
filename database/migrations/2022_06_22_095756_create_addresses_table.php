<?php

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
      Schema::create('addresses', function (Blueprint $table) {
         $table->id();
         $table->foreignIdFor(User::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
         $table->string('address');
         $table->string('sub_district', 100);
         $table->integer('city_id');
         $table->string('city', 100);
         $table->integer('province_id');
         $table->string('province', 100);
         $table->bigInteger('postal_code');
         $table->boolean('flags')->default(0);
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
      Schema::dropIfExists('addresses');
   }
};
