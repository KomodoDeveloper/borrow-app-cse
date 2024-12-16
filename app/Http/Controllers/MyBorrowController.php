<?php

namespace App\Http\Controllers;

use App\Models\Borrow;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MyBorrowController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkaai');
    }

    public function index(): View
    {
        $myBorrows = Borrow::where('email_borrower', Auth::user()->email)->get();
        $myBorrowsFiltered = $myBorrows->where('status', '!=', 'to_control');

        //dd($myBorrowsFiltered);
        return view('myborrows', [
            'myborrows' => $myBorrowsFiltered,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        //
    }
}
