<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BorrowController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MyBorrowController;
use App\Http\Controllers\StateOfBorrowController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/*
Route::get('/', function () {
    return view('home');
});
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/login', [HomeController::class, 'login'])->name('login');
Route::get('/logout', [HomeController::class, 'logout'])->name('logout');

Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
Route::get('/scan', [AdminController::class, 'scan'])->name('admin.scan');
Route::post('/details', [AdminController::class, 'getElementByCode'])->name('admin.getElementByCode');
Route::get('/archive/borrows', [AdminController::class, 'archiveBorrowsIndex'])->name('admin.archive');
Route::get('/listinventorycollaborators/', [AdminController::class, 'listInventoryCollaborators'])->name('admin.listinventorycollab');
Route::get('/listinventorymultimedia/', [AdminController::class, 'listInventoryMultimedia'])->name('admin.listinventorymultimedia');

Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/catalog/{category_option}', [CatalogController::class, 'getCategory'])->name('catalog.getCategory');
Route::get('/intern/catalog', [CatalogController::class, 'indexIntern'])->name('catalog.indexIntern');
Route::get('/intern/catalog/{category_option}', [CatalogController::class, 'internGetCategory'])->name('catalog.internGetCategory');

Route::get('/myborrows', [MyBorrowController::class, 'index'])->name('myborrow.index');

Route::get('/stateofborrow', [StateOfBorrowController::class, 'index'])->name('stateofborrow.index');
Route::get('/stateofborrow/{borrow_id}/return', [StateOfBorrowController::class, 'returnBorrow'])->name('stateofborrow.return');
Route::put('/stateofborrow/updateavailability', [StateOfBorrowController::class, 'updateAvailability'])->name('stateofborrow.updateavailability');
Route::get('/stateofborrow/{borrow_id}/delete', [StateOfBorrowController::class, 'destroy'])->name('stateofborrow.delete');
Route::get('/stateofborrow/tocontrol', [StateOfBorrowController::class, 'tocontrolindex'])->name('stateofborrow.tocontrolindex');
Route::get('/stateofborrow/{borow_id}/close', [StateOfBorrowController::class, 'archivedBorrow'])->name('stateofborrow.close');

Route::get('/borrow/new', [BorrowController::class, 'create'])->name('borrow.create');
Route::get('/borrow/many', [BorrowController::class, 'createmany'])->name('borrow.createmany');
Route::post('/borrow/store', [BorrowController::class, 'store'])->name('borrow.store');
Route::post('/borrow/storemany', [BorrowController::class, 'storemany'])->name('borrow.storemany');
Route::get('/borrow/new/{equipment_id}', [BorrowController::class, 'customcreate'])->name('borrow.customcreate');
Route::get('/borrow/{id}/edit', [BorrowController::class, 'edit'])->name('borrow.edit');
Route::put('/borrow/{id}/update', [BorrowController::class, 'update'])->name('borrow.update');
Route::post('/borrow/new/planned', [BorrowController::class, 'planned'])->name('borrow.planned');

Route::get('/equipment/add', [EquipmentController::class, 'create'])->name('equipment.create');
Route::post('/equipment/store', [EquipmentController::class, 'store'])->name('equipment.store');
Route::get('/equipment/{equipment_id}', [EquipmentController::class, 'show'])->name('equipment.show');
Route::get('/equipment/{id}/edit', [EquipmentController::class, 'edit'])->name('equipment.edit');
Route::put('/equipment/{id}/update', [EquipmentController::class, 'update'])->name('equipment.update');
Route::get('/equipment/{id}/destroy', [EquipmentController::class, 'destroy'])->name('equipment.destroy');
Route::get('/equipment/{id}/duplicate', [EquipmentController::class, 'duplicate'])->name('equipment.duplicate');
Route::post('/equipment/{id}/storeCopy', [EquipmentController::class, 'storeCopy'])->name('equipment.storeCopy');

Route::get('/category/manager', [CategoryController::class, 'index'])->name('category.index');
Route::get('/category/add', [CategoryController::class, 'create'])->name('category.create');
Route::post('/category/store', [CategoryController::class, 'store'])->name('category.store');
Route::get('/category/{id}/edit', [CategoryController::class, 'edit'])->name('category.edit');
Route::put('/category/{id}/update', [CategoryController::class, 'update'])->name('category.update');
Route::get('/category/{id}/destroy', [CategoryController::class, 'destroy'])->name('category.destroy');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

Route::get('/aide', function () {
    return view('help');
})->name('help');
/*
Route::get('/login', function () {
    return redirect()->route('home');
});
*/
