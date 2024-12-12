<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkaai');
    }

    public function index()
    {
        $categories = Categories::all();
        return view('categories', [
            'categories' => $categories
        ]);
    }

    public function create()
    {
        return view('addcategory');
    }

    public function store(Request $request)
    {
        //dd($request);
        $category = new Categories();
        $category->name = $request->input('name');
        $category->description = $request->input('description');

        $category->save();

        return redirect()->route('category.index')->with('addCategory', 'Nouvelle catégorie ajoutée');
    }

    public function edit($id)
    {
        $category = Categories::find($id);
        return view('editcategory', [
            'category' => $category
        ]);
    }

    public function update(Request $request, $id)
    {
        $category = Categories::find($id);
        $category->name = $request->input('name');
        $category->description = $request->input('description');

        $category->save();
        return redirect()->route('category.index')->with('updateCategory', 'Catégorie mise à jour');
    }

    public function destroy($id)
    {
        $category = Categories::find($id);
        $category->equipments()->detach($category->equipments()->get());
        $category->delete();

        return redirect()->route('category.index');
    }
}
