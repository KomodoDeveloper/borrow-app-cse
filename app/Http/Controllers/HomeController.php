<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Equipments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
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
    public function index()
    {
        $categories = Categories::all();
        $categories = $categories->sortBy('name');
        //$equipments = Equipments::where('internal',0)->get();
        //$recentEquipments = $equipments->sortByDesc('created_at')->take(6);
        $rodeMicForExample = Equipments::where('name','LIKE', 'Rode NT-USB mini')->first();
        return view('home', ['rodeMicForExample'=>$rodeMicForExample, 'categories'=>$categories]);
    }


    public function logout()
    {
	    Auth::logout();
	    return redirect('https://' . request()->getHttpHost() . '/Shibboleth.sso/Logout');
    }


    public function login()
    {
        return redirect()->route('home');
    }

}
