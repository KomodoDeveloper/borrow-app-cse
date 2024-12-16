<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Categories;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkaai');
    }

    public function index(): View
    {
        $categories = Categories::all();

        return view('categories', [
            'categories' => $categories,
        ]);
    }

    public function create(): View
    {
        return view('addcategory');
    }

    public function store(Request $request): RedirectResponse
    {
        //dd($request);
        $category = new Categories;
        $category->name = $request->input('name');
        $category->description = $request->input('description');

        $category->save();

        return redirect()->route('category.index')->with('addCategory', 'Nouvelle catégorie ajoutée');
    }

    public function edit($id): View
    {
        $category = Categories::find($id);

        return view('editcategory', [
            'category' => $category,
        ]);
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $category = Categories::find($id);
        $category->name = $request->input('name');
        $category->description = $request->input('description');

        $category->save();

        return redirect()->route('category.index')->with('updateCategory', 'Catégorie mise à jour');
    }

    public function destroy($id): RedirectResponse
    {
        $category = Categories::find($id);
        $category->equipments()->detach($category->equipments()->get());
        $category->delete();

        return redirect()->route('category.index');
    }
}
