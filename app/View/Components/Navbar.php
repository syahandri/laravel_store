<?php

namespace App\View\Components;

use App\Models\Cart;
use App\Models\Category;
use Illuminate\View\Component;

class Navbar extends Component
{
   /**
    * Create a new component instance.
    *
    * @return void
    */
   public function __construct()
   {
      //
   }

   /**
    * Get the view / contents that represent the component.
    *
    * @return \Illuminate\Contracts\View\View|\Closure|string
    */
   public function render()
   {
      if (auth()->check()) {
         $user_id = auth()->user()->id;
         $cart = Cart::where('user_id', $user_id)->first();
         $count = $cart ? $cart->products->count() : 0;
      }
      return view('components.navbar', [
         'categories' => Category::all(),
         'cart_count' => auth()->check() ? ($count < 100 ? $count : '99+') : 0
      ]);
   }
}
