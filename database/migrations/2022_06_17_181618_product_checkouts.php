<?php

use App\Models\Checkout;
use App\Models\Product;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
      Schema::create('checkout_product', function (Blueprint $table) {
         $table->foreignIdFor(Checkout::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
         $table->foreignIdFor(Product::class)->constrained()->restrictOnUpdate()->restrictOnDelete();
         $table->string('color', 15);
         $table->string('size', 5);
         $table->integer('price');
         $table->integer('quantity');
         $table->integer('sub_total');
         $table->text('note')->nullable();
         $table->timestamps();
      });

      // Trigger stock product
      DB::statement('CREATE TRIGGER `min_stock` AFTER INSERT ON `checkout_product` FOR EACH ROW UPDATE products SET stock = stock - NEW.quantity WHERE id = NEW.product_id');

      // DB::statement('CREATE TRIGGER `update_stock` AFTER UPDATE ON `checkout_product` FOR EACH ROW UPDATE products SET stock = (stock + OLD.quantity) - NEW.quantity WHERE id = OLD.product_id');
      
      // DB::statement('CREATE TRIGGER `reset_stock` AFTER DELETE ON `checkout_product` FOR EACH ROW UPDATE products SET stock = stock + OLD.quantity WHERE id = OLD.product_id');

      // Trigger sold
      DB::statement('CREATE TRIGGER `plus_sold` AFTER INSERT ON `checkout_product` FOR EACH ROW UPDATE products SET sold = sold + NEW.quantity WHERE id = NEW.product_id');
   }

   /**
    * Reverse the migrations.
    *
    * @return void
    */
   public function down()
   {
      Schema::dropIfExists('checkout_product');
   }
};
