<?php

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
    return view('welcome');
});
*/



// Route::get('/', function () {
// 	return view('index');
// });

//index page
Route::get('/', 'PopulationController@index')->name('dashboard');
//form export
Route::get('export-csv', 'PopulationController@export_form')->name('export.form');
//view by disease
//Route::get('showbydisease/{disease_code}/{year}','PopulationController@ShowByDisease')->where(['disease_code' => '[0-9]+','year' => '[0-9]+']);
Route::get('showbydisease','PopulationController@ShowByDisease')->name('showbydisease');


//export xls From disease name and year
Route::post('exportbydisease','PopulationController@ExportByDiseaseXLS')->name('exportbydisease');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
