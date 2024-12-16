<?php

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
Route::get('/', 'HomeController@index')->name('home');
Route::get('/login', 'HomeController@login')->name('login');
Route::get('/logout', 'HomeController@logout')->name('logout');

Route::get('/admin', 'AdminController@index')->name('admin.index');
Route::get('/scan', 'AdminController@scan')->name('admin.scan');
Route::post('/details', 'AdminController@getElementByCode')->name('admin.getElementByCode');
Route::get('/archive/borrows', 'AdminController@archiveBorrowsIndex')->name('admin.archive');
Route::get('/listinventorycollaborators/', 'AdminController@listInventoryCollaborators')->name('admin.listinventorycollab');
Route::get('/listinventorymultimedia/', 'AdminController@listInventoryMultimedia')->name('admin.listinventorymultimedia');

Route::get('/catalog', 'CatalogController@index')->name('catalog.index');
Route::get('/catalog/{category_option}', 'CatalogController@getCategory')->name('catalog.getCategory');
Route::get('/intern/catalog', 'CatalogController@indexIntern')->name('catalog.indexIntern');
Route::get('/intern/catalog/{category_option}', 'CatalogController@internGetCategory')->name('catalog.internGetCategory');

Route::get('/myborrows', 'MyBorrowController@index')->name('myborrow.index');

Route::get('/stateofborrow', 'StateOfBorrowController@index')->name('stateofborrow.index');
Route::get('/stateofborrow/{borrow_id}/return', 'StateOfBorrowController@returnBorrow')->name('stateofborrow.return');
Route::put('/stateofborrow/updateavailability', 'StateOfBorrowController@updateAvailability')->name('stateofborrow.updateavailability');
Route::get('/stateofborrow/{borrow_id}/delete', 'StateOfBorrowController@destroy')->name('stateofborrow.delete');
Route::get('/stateofborrow/tocontrol', 'StateOfBorrowController@tocontrolindex')->name('stateofborrow.tocontrolindex');
Route::get('/stateofborrow/{borow_id}/close', 'StateOfBorrowController@archivedBorrow')->name('stateofborrow.close');

Route::get('/borrow/new', 'BorrowController@create')->name('borrow.create');
Route::get('/borrow/many', 'BorrowController@createmany')->name('borrow.createmany');
Route::post('/borrow/store', 'BorrowController@store')->name('borrow.store');
Route::post('/borrow/storemany', 'BorrowController@storemany')->name('borrow.storemany');
Route::get('/borrow/new/{equipment_id}', 'BorrowController@customcreate')->name('borrow.customcreate');
Route::get('/borrow/{id}/edit', 'BorrowController@edit')->name('borrow.edit');
Route::put('/borrow/{id}/update', 'BorrowController@update')->name('borrow.update');
Route::post('/borrow/new/planned', 'BorrowController@planned')->name('borrow.planned');

Route::get('/equipment/add', 'EquipmentController@create')->name('equipment.create');
Route::post('/equipment/store', 'EquipmentController@store')->name('equipment.store');
Route::get('/equipment/{equipment_id}', 'EquipmentController@show')->name('equipment.show');
Route::get('/equipment/{id}/edit', 'EquipmentController@edit')->name('equipment.edit');
Route::put('/equipment/{id}/update', 'EquipmentController@update')->name('equipment.update');
Route::get('/equipment/{id}/destroy', 'EquipmentController@destroy')->name('equipment.destroy');
Route::get('/equipment/{id}/duplicate', 'EquipmentController@duplicate')->name('equipment.duplicate');
Route::post('/equipment/{id}/storeCopy', 'EquipmentController@storeCopy')->name('equipment.storeCopy');

Route::get('/category/manager', 'CategoryController@index')->name('category.index');
Route::get('/category/add', 'CategoryController@create')->name('category.create');
Route::post('/category/store', 'CategoryController@store')->name('category.store');
Route::get('/category/{id}/edit', 'CategoryController@edit')->name('category.edit');
Route::put('/category/{id}/update', 'CategoryController@update')->name('category.update');
Route::get('/category/{id}/destroy', 'CategoryController@destroy')->name('category.destroy');

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
