<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Models\Categories;
use App\Models\Equipments;

class CatalogController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkaai');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): View
    {
        $categories = Categories::all();
        $categories = $categories->sortBy('name');
        $equipmentsNotSorted = Equipments::where('internal', 0)->get();
        $equipments = $equipmentsNotSorted->sortByDesc('created_at');

        return view('catalog', ['equipments' => $equipments, 'categories' => $categories]);
    }

    public function indexIntern(): View
    {
        $categories = Categories::all();
        $categories = $categories->sortBy('name');
        $equipmentsNotSorted = Equipments::all();
        $equipments = $equipmentsNotSorted->sortByDesc('created_at');

        return view('catalogintern', ['equipments' => $equipments, 'categories' => $categories]);
    }

    public function getCategory($getCategory): View
    {
        $categories = Categories::all();
        $equipmentsNotSorted = Equipments::whereHas('categories', function ($q) use ($getCategory) {
            $q->where('categories_id', $getCategory);
        })->get();
        $equipmentsNotSorted = $equipmentsNotSorted->where('internal', 0);
        $equipments = $equipmentsNotSorted->sortByDesc('created_at');
        //$equipments->categories()->wherePivot('categories_id','==', $getCategory)->get(); marche pas sur une collection mais à retenir
        //$equipments = Equipments::where('category_id', $getCategory)->get();

        return view('catalog', ['equipments' => $equipments, 'categories' => $categories]);
    }

    public function internGetCategory($getCategory): View
    {
        $categories = Categories::all();
        $equipmentsNotSorted = Equipments::whereHas('categories', function ($q) use ($getCategory) {
            $q->where('categories_id', $getCategory);
        })->get();
        $equipments = $equipmentsNotSorted->sortByDesc('created_at');

        return view('catalogintern', ['equipments' => $equipments, 'categories' => $categories]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        //
    }
}
