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
Route::get('/', function () {
	return view('index');
});
*/
Auth::routes();
/* Login */
Route::get('/home', 'HomeController@index')->name('home');
/* index */
Route::get('/', 'DashboardController@index')->name('dashboard');

/* population */
Route::get('/population', 'PopulationController@index')->name('population');
/* view by disease */
Route::get('showbydisease','PopulationController@ShowByDisease')->name('showbydisease');
// Route::get('showbydisease/{disease_code}/{year}','PopulationController@ShowByDisease')->where(['disease_code' => '[0-9]+','year' => '[0-9]+']);
/* form export */
Route::get('export-csv', 'PopulationController@export_form')->name('export.form');
/* export xls From disease name and year */
Route::post('exportbydisease','PopulationController@ExportByDiseaseXLS')->name('exportbydisease');
/* Report */
Route::get('/report', function() {
	return view('frontend.report');
})->name('report');
/* ******** backend ******** */
Route::get('/backend', function() {
	return view('backend/index');
});
