<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Role;
use App\Models\Size;
use App\Models\User;
use App\Models\Color;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
   /**
    * Seed the application's database.
    *
    * @return void
    */
   public function run()
   {
      // \App\Models\User::factory(2)->create();

      User::create([
         'role_id' => 1,
         'name' => 'Administrator',
         'email' => 'admin@store.com',
         'phone' => '087812349876',
         'email_verified_at' => now(),
         'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
         'remember_token' => str()->random(10),
      ]);

      User::create([
         'name' => 'John Breet',
         'email' => 'johnbreet@gmail.com',
         'phone' => '087898761234',
         'email_verified_at' => now(),
         'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
         'remember_token' => str()->random(10),
      ]);

      $roles = [
         ['name' => 'Admin'],
         ['name' => 'User']
      ];

      foreach ($roles as $role) {
         Role::create($role);
      }

      $categories = [
         [
            'name' => 'Aksesoris',
            'slug' => 'aksesoris',
         ],
         [
            'name' => 'Jaket',
            'slug' => 'jaket',
         ],
         [
            'name' => 'Celana',
            'slug' => 'celana',
         ],
         [
            'name' => 'Kaos',
            'slug' => 'kaos',
         ],
         [
            'name' => 'Kemeja',
            'slug' => 'kemeja',
         ]
      ];

      $sizes = [
         [
            'name' => 'S',
         ],
         [
            'name' => 'M',
         ],
         [
            'name' => 'L',
         ],
         [
            'name' => 'XL',
         ],
         [
            'name' => 'XXL',
         ],
      ];

      $colors = [
         [
            'name' => 'black',
         ],
         [
            'name' => 'navy',
         ],
         [
            'name' => 'blue',
         ],
         [
            'name' => 'darkgreen',
         ],
         [
            'name' => 'green',
         ],
         [
            'name' => 'teal',
         ],
         [
            'name' => 'deepskyblue',
         ],
         [
            'name' => 'lime',
         ],
         [
            'name' => 'indigo',
         ],
         [
            'name' => 'maroon',
         ],
         [
            'name' => 'purple',
         ],
         [
            'name' => 'gray',
         ],
         [
            'name' => 'grey',
         ],
         [
            'name' => 'darkred',
         ],
         [
            'name' => 'brown',
         ],
         [
            'name' => 'salmon',
         ],
         [
            'name' => 'red',
         ],
         [
            'name' => 'deeppink',
         ],
         [
            'name' => 'orangered',
         ],
         [
            'name' => 'hotpink',
         ],
         [
            'name' => 'orange',
         ],
         [
            'name' => 'lightpink',
         ],
         [
            'name' => 'pink',
         ],
         [
            'name' => 'gold',
         ],
         [
            'name' => 'yellow',
         ],
         [
            'name' => 'white',
         ]
      ];

      foreach ($categories as $category) {
         Category::create($category);
      }

      foreach ($colors as $color) {
         Color::create($color);
      }

      foreach ($sizes as $size) {
         Size::create($size);
      }

      // Product::factory(100)->create();

      // for ($i = 0; $i < 300; $i++) {
      //    $category_product = [
      //       'product_id' => mt_rand(1, 100),
      //       'category_id' => mt_rand(1, 5)
      //    ];

      //    DB::table('category_product')->insert($category_product);
      // }

      // for ($i = 0; $i < 300; $i++) {
      //    $color_product = [
      //       'product_id' => mt_rand(1, 100),
      //       'color_id' => mt_rand(1, 26)
      //    ];

      //    DB::table('color_product')->insert($color_product);
      // }

      // for ($i = 0; $i < 300; $i++) {
      //    $product_size = [
      //       'product_id' => mt_rand(1, 100),
      //       'size_id' => mt_rand(1, 5)
      //    ];

      //    DB::table('product_size')->insert($product_size);
      // }
   }
}
