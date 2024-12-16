<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Equipments;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkaai');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $categories = Categories::all();
        $categories = $categories->sortBy('name');
        //$equipments = Equipments::where('internal',0)->get();
        //$recentEquipments = $equipments->sortByDesc('created_at')->take(6);
        $rodeMicForExample = Equipments::where('name', 'LIKE', 'Rode NT-USB mini')->first();

        return view('home', ['rodeMicForExample' => $rodeMicForExample, 'categories' => $categories]);
    }

    public function logout(): RedirectResponse
    {
        Auth::logout();

        return redirect('https://'.request()->getHttpHost().'/Shibboleth.sso/Logout');
    }

    public function login(): RedirectResponse
    {
        return redirect()->route('home');
    }
}
