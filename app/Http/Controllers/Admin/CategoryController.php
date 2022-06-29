<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
   /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function index()
   {
      return view('admin.category.index', [
         'title' => 'Kelola Kategori',
         'categories' => Category::withCount('products')->get()
      ]);
   }

   /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
   public function store(Request $request)
   {
      $validator = Validator::make($request->all(), [
         'name' => 'required|unique:categories'
      ]);

      if ($validator->fails()) {
         return ['code' => 422, 'invalid' => $validator->getMessageBag()];
      }

      $validated = $validator->validated();
      $validated['slug'] = str()->slug($validated['name']);

      try {
         Category::create($validated);
         return ['code' => 200, 'message' => 'Kategori berhasil ditambahkan.'];
      } catch (\Throwable $th) {
         return ['code' => 500, 'message' => 'Kategori gagal ditambahkan, silahkan coba lagi.'];
      }
   }

   /**
    * Display the specified resource.
    *
    * @param  \App\Models\Category  $category
    * @return \Illuminate\Http\Response
    */
   public function show(Category $category)
   {
      return ['code' => 200, 'result' => $category];
   }

   /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Models\Category  $category
    * @return \Illuminate\Http\Response
    */
   public function update(Request $request, Category $category)
   {
      $validator = Validator::make($request->all(), [
         'name' => 'required|unique:categories,name,' . $category->id
      ]);

      if ($validator->fails()) {
         return ['code' => 422, 'invalid' => $validator->getMessageBag()];
      }

      $validated = $validator->validated();
      $validated['slug'] = str()->slug($validated['name']);

      try {
         $category->update($validated);
         return ['code' => 200, 'message' => 'Kategori berhasil diubah.'];
      } catch (\Throwable $th) {
         return ['code' => 500, 'message' => 'Kategori gagal diubah, silahkan coba lagi.'];
      }
   }

   /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Models\Category  $category
    * @return \Illuminate\Http\Response
    */
   public function destroy(Category $category)
   {
      try {
         $category->delete();
         return ['code' => 200, 'message' => 'Kategori berhasil dihapus.'];
      } catch (\Throwable $th) {
         return ['code' => 500, 'message' => 'Kategori gagal dihapus, silahkan coba lagi.'];
      }
   }
}
