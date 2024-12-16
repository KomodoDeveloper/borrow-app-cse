<?php

namespace App\Http\Controllers;

use App\Models\Borrow;
use App\Models\Categories;
use App\Models\Equipments;
use Illuminate\Http\Request;

class EquipmentController extends Controller
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
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Categories::all();

        return view('addequipment', [
            'categories' => $categories,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $codeGenerate = 0;

        $tmpequi = Equipments::all();
        $tmpequi = $tmpequi->sortByDesc('code')->first();
        if ($tmpequi == null) {
            // start the counter on the first equipment entry in the db table
            $codeGenerate = 100001;
        } else {
            // countinue count with previous code + 1
            $codeGenerate = $tmpequi->code + 1;
        }

        $equipment = new Equipments;
        $equipment->name = $request->input('name');
        $equipment->description = $request->input('description');
        $equipment->seriallNumber = $request->input('seriallNumber');
        $equipment->ci_number = $request->input('ci_number');
        $equipment->code = $codeGenerate;

        if ($request->input('internal') == 'true') {
            $equipment->internal = 1;
        } else {
            $equipment->internal = 0;
        }

        //check if pruduct year is not empty and if it's a valid year
        if (! empty($request->input('product_year'))) {
            if (is_numeric($request->input('product_year'))) {
                $inputProductYear = (int) $request->input('product_year');
                if ($inputProductYear <= 1900 || $inputProductYear >= 2100) {
                    return redirect()->back()->with('errorInField', 'année de production doit être comprise entre 1900 et 2100');
                } else {
                    $equipment->product_year = $inputProductYear;
                }
            } else {
                return redirect()->back()->with('errorInField', 'année de production doit être un nombre');
            }
        }

        //check if purchase_date_month and purchase_date_year are set or not and if it is, tansform to date format and calcul the expiration_date
        if ($request->input('purchase_date_month') != '99' && $request->input('purchase_date_year') != '9999') {
            // attempt format : 'yyyy-mm-dd'
            $transformToDate = $request->input('purchase_date_year').'-'.$request->input('purchase_date_month').'-'.'15';
            $equipment->purchase_date = $transformToDate;
            $equipment->expiration_date = date('Y-m-d', strtotime($transformToDate.' + 6 years'));
        }

        $image = $request->file('image');
        $imageFullName = $image->getClientOriginalName();
        $imageName = pathinfo($imageFullName, PATHINFO_FILENAME);
        $extension = $image->getClientOriginalExtension();
        $file = time().'_'.$imageName.'.'.$extension;
        $image->storeAs('public/equipments_images', $file);

        $equipment->image = $file;

        $categoriesFromRequest = $request->input('categories');
        $equipment->save();

        $equipment->categories()->attach($categoriesFromRequest);

        return redirect()->route('equipment.show', $equipment->id)->with('updateEquipment', 'Objet ajouté');
        //return redirect()->route('admin.index')->with('success', 'File upload successfully');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $equipment_id
     */
    public function show($equipment_id)
    {
        $equipment = Equipments::find($equipment_id);
        if ($equipment->availability == 0) {
            $borrow = Borrow::where('equipment_id', '=', $equipment_id)->where('status', '=', 'borrowed')->first();

            if ($borrow == null) {
                $borrow = Borrow::where('equipment_id', '=', $equipment_id)->where('status', '=', 'to_control')->get();
                $borrow = $borrow->sortByDesc('updated_at')->first();
                if ($borrow !== null) {
                    return view('equipment', compact('equipment', 'borrow'));
                } else {
                    $borrow = Borrow::where('equipment_id', '=', $equipment_id)->where('status', '=', 'finish')->get();
                    $borrow = $borrow->sortByDesc('updated_at')->first();
                }
            }

            return view('equipment', compact('equipment', 'borrow'));
        }

        //dd($borrow);
        return view('equipment', [
            'equipment' => $equipment,
        ]);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     */
    public function edit($id)
    {
        $equipment = Equipments::find($id);
        $categories = Categories::all();
        if (! empty($equipment->purchase_date)) {
            $orignalPurchaseDateAsSplitArray = explode('-', $equipment->purchase_date);
            $stored_purchase_month = $orignalPurchaseDateAsSplitArray[1];
            $stored_purchase_year = $orignalPurchaseDateAsSplitArray[0];
        } else {
            $stored_purchase_month = '99';
            $stored_purchase_year = '9999';
        }

        //dd($equipment,$categories,$id,$orignalPurchaseDateAsSplitArray,$stored_purchase_month,$stored_purchase_year);
        return view('editequipment', [
            'equipment' => $equipment,
            'categories' => $categories,
            'stored_purchase_month' => $stored_purchase_month,
            'stored_purchase_year' => $stored_purchase_year,
        ]);
    }

    /**
     * show form for duplicate a equipment
     *
     * @param  int  $id
     */
    public function duplicate($id)
    {
        $equipment = Equipments::find($id);
        $categories = Categories::all();

        //dd($equipment,$categories,$id);
        return view('duplicateequipment', [
            'equipment' => $equipment,
            'categories' => $categories,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $equipment = Equipments::find($id);
        $equipment->name = $request->input('name');
        $equipment->description = $request->input('description');
        $equipment->seriallNumber = $request->input('seriallNumber');
        $equipment->ci_number = $request->input('ci_number');

        if ($request->input('internal') == 'true') {
            $equipment->internal = 1;
        } else {
            $equipment->internal = 0;
        }

        if ($request->input('is_out_of_service') == 'true') {
            $equipment->is_out_of_service = 1;
        } else {
            $equipment->is_out_of_service = 0;
        }

        //check if pruduct year is not empty and if it's a valid year
        if (! empty($request->input('product_year'))) {
            if (is_numeric($request->input('product_year'))) {
                $inputProductYear = (int) $request->input('product_year');
                if ($inputProductYear <= 1900 || $inputProductYear >= 2100) {
                    return redirect()->back()->with('errorInField', 'année de production doit être comprise entre 1900 et 2100');
                } else {
                    $equipment->product_year = $inputProductYear;
                }
            } else {
                return redirect()->back()->with('errorInField', 'année de production doit être un nombre');
            }
        }

        //check if purchase_date_month and purchase_date_year are set or not and if it is, tansform to date format and calcul the expiration_date
        if ($request->input('purchase_date_month') != '99' && $request->input('purchase_date_year') != '9999') {
            // attempt format : 'yyyy-mm-dd'
            $transformToDate = $request->input('purchase_date_year').'-'.$request->input('purchase_date_month').'-'.'15';
            $equipment->purchase_date = $transformToDate;
            $equipment->expiration_date = date('Y-m-d', strtotime($transformToDate.' + 6 years'));
        }

        if ($request->file('image')) {
            $image = $request->file('image');
            $imageFullName = $image->getClientOriginalName();
            $imageName = pathinfo($imageFullName, PATHINFO_FILENAME);
            $extension = $image->getClientOriginalExtension();
            $file = time().'_'.$imageName.'.'.$extension;
            $image->storeAs('public/equipments_images', $file);
            $equipment->image = $file;
        }

        $categoriesFromRequest = $request->input('categories');
        $equipment->save();
        $equipment->categories()->detach($equipment->categories()->get());
        $equipment->categories()->attach($categoriesFromRequest);

        return redirect()->route('equipment.show', $id)->with('updateEquipment', 'Objet mis à jour');
    }

    /**
     * Store a newly created resource in storage from duplication
     *
     * @return \Illuminate\Http\Response
     */
    public function storeCopy(Request $request, $id)
    {
        $equipmentBaseOn = Equipments::find($id);
        $codeGenerate = 0;

        $tmpequi = Equipments::all();
        $tmpequi = $tmpequi->sortByDesc('code')->first();
        if ($tmpequi == null) {
            // start the counter on the first equipment entry in the db table
            $codeGenerate = 100001;
        } else {
            // countinue count with previous code + 1
            $codeGenerate = $tmpequi->code + 1;
        }

        $equipment = new Equipments;
        $equipment->name = $request->input('name');
        $equipment->description = $request->input('description');
        $equipment->seriallNumber = $request->input('seriallNumber');
        $equipment->ci_number = $request->input('ci_number');
        $equipment->code = $codeGenerate;

        if ($request->input('internal') == 'true') {
            $equipment->internal = 1;
        } else {
            $equipment->internal = 0;
        }

        //check if pruduct year is not empty and if it's a valid year
        if (! empty($request->input('product_year'))) {
            if (is_numeric($request->input('product_year'))) {
                $inputProductYear = (int) $request->input('product_year');
                if ($inputProductYear <= 1900 || $inputProductYear >= 2100) {
                    return redirect()->back()->with('errorInField', 'année de production doit être comprise entre 1900 et 2100');
                } else {
                    $equipment->product_year = $inputProductYear;
                }
            } else {
                return redirect()->back()->with('errorInField', 'année de production doit être un nombre');
            }
        }

        //check if purchase_date_month and purchase_date_year are set or not and if it is, tansform to date format and calcul the expiration_date
        if ($request->input('purchase_date_month') != '99' && $request->input('purchase_date_year') != '9999') {
            // attempt format : 'yyyy-mm-dd'
            $transformToDate = $request->input('purchase_date_year').'-'.$request->input('purchase_date_month').'-'.'15';
            $equipment->purchase_date = $transformToDate;
            $equipment->expiration_date = date('Y-m-d', strtotime($transformToDate.' + 6 years'));
        }

        if ($request->file('image')) {
            $image = $request->file('image');
            $imageFullName = $image->getClientOriginalName();
            $imageName = pathinfo($imageFullName, PATHINFO_FILENAME);
            $extension = $image->getClientOriginalExtension();
            $file = time().'_'.$imageName.'.'.$extension;
            $image->storeAs('public/equipments_images', $file);
            $equipment->image = $file;
        } else {
            $equipment->image = $equipmentBaseOn->image;
        }

        $categoriesFromRequest = $request->input('categories');
        $equipment->save();

        $equipment->categories()->attach($categoriesFromRequest);

        return redirect()->route('equipment.show', $equipment->id)->with('updateEquipment', 'Objet copié');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // check if there are still borrow for this equipment
        $borrowsForEquipment = Borrow::where('equipment_id', $id)->get();
        if ($borrowsForEquipment->count() === 0) {
            $equipment = Equipments::find($id);
            $equipment->categories()->detach($equipment->categories()->get());
            $equipment->delete();

            return redirect()->route('catalog.index');
        } else {
            return redirect()->route('equipment.show', $id)->with('warningEquipment', 'Attention ! Emprunt(s) concernant cet objet toujours en cours ! Vérifiez d\'abord l\'état des prêts');
        }
    }
}
