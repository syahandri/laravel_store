<?php

use App\Models\Product;
use App\Models\InvoiceCancel;
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
      Schema::create('invoice_cancel_product', function (Blueprint $table) {
         $table->foreignIdFor(InvoiceCancel::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
         $table->foreignIdFor(Product::class)->constrained()->restrictOnUpdate()->restrictOnDelete();
         $table->string('color', 15);
         $table->string('size', 5);
         $table->integer('price');
         $table->integer('quantity');
         $table->integer('sub_total');
         $table->text('note')->nullable();
         $table->timestamps();
      });

      // Trigger stock
      DB::statement('CREATE TRIGGER `reset_stock` AFTER INSERT ON `invoice_cancel_product` FOR EACH ROW UPDATE products SET stock = stock + NEW.quantity WHERE id = NEW.product_id');

      // Trigger sold
      DB::statement('CREATE TRIGGER `reset_sold` AFTER INSERT ON `invoice_cancel_product` FOR EACH ROW UPDATE products SET sold = sold - NEW.quantity WHERE id = NEW.product_id');
   }

   /**
    * Reverse the migrations.
    *
    * @return void
    */
   public function down()
   {
      Schema::dropIfExists('invoice_cancel_product');
   }
};
