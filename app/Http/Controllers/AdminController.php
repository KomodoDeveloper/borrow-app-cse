<?php

namespace App\Http\Controllers;

use App\Models\ArchiveBorrow;
use App\Models\Equipments;
use DateInterval;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
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
        //echo '<pre>';
        //print_r($_SERVER);
        //die();
        return view('admin');
    }

    public function scan()
    {
        return view('scan');
    }

    public function getElementByCode(Request $request)
    {
       if (Equipments::where('code', '=', $request->input('code'))->exists()) {
           $equipemt = Equipments::where('code', '=', $request->input('code'))->first();
           return redirect()->route('equipment.show',  $equipemt->id);
       }else{
           return back()->with('warning', 'Code not found in the database');
       }

    }

    public function archiveBorrowsIndex()
    {
        $archiveBorrows = ArchiveBorrow::orderBy('a_equipment_id','asc')->orderBy('a_start_date', 'desc')->get();
        //dd($archiveBorrows);
        return view('archive', [
            'archiveBorrows' => $archiveBorrows
        ]);
    }

    public function listInventoryCollaborators()
    {
        $allInventoryCollaborators = DB::table('borrows')
            ->join('users', 'users.email', '=', 'borrows.email_borrower')
            ->join('equipments', 'equipments.id', '=', 'borrows.equipment_id')
            ->select( 'borrows.id', 'borrows.first_name_borrower', 'borrows.surname_borrower','users.email', 'borrows.email_borrower' ,'borrows.start_date' ,'borrows.end_date','equipments.code','equipments.ci_number','equipments.name','equipments.product_year','equipments.purchase_date','equipments.expiration_date')
            ->where('users.is_cse_member', '=', 1)
            ->get();

        $countExpiredEquipment = 0;
        $countLessThanSixMonthsBeforeExpiration = 0;
        $dateNow = new DateTime();
        $dateNow = $dateNow->format('Y-m-d');
        $dateNowPlusSixMonths = new DateTime();
        $dateNowPlusSixMonths->add(new DateInterval('P6M'));
        $dateNowPlusSixMonths = $dateNowPlusSixMonths->format('Y-m-d');

        foreach ($allInventoryCollaborators as $objetInventoryCollab) {
            if(!empty($objetInventoryCollab->expiration_date)) {
                if ($objetInventoryCollab->expiration_date < $dateNow) {
                    $countExpiredEquipment += 1;
                } elseif ($objetInventoryCollab->expiration_date < $dateNowPlusSixMonths && $objetInventoryCollab->expiration_date > $dateNow) {
                    $countLessThanSixMonthsBeforeExpiration += 1;
                }
            }
        }

        return view('listinventorycollaborators', [
            'allInventoryCollaborators' => $allInventoryCollaborators,
            'countExpiredEquipment' => $countExpiredEquipment,
            'countLessThanSixMonthsBeforeExpiration' => $countLessThanSixMonthsBeforeExpiration
        ]);
    }

    public function listInventoryMultimedia()
    {
        // get all equipments tagged by multimedian category and give them to view datatable like
        $allEquipments = Equipments::all();
        $allEquipmentsTaggedMultimedia = [];
        $selectedCategory = Config::get('constants.others.categorySelected');
        foreach ($allEquipments as $equipment){
            $itsCategories = $equipment->categories()->get();
            if ($itsCategories->contains('name', $selectedCategory)){
                $allEquipmentsTaggedMultimedia[] = $equipment;
            }
        }

        return view('listinventorymultimedia', [
            'allEquipmentsTaggedMultimedia' => $allEquipmentsTaggedMultimedia
        ]);
    }

}
