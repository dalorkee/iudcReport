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
Route::get('/dbd', 'DashboardController@index')->name('dbd');

/* population */
Route::get('/population', 'PopulationController@index')->name('population');
/* view by disease */
Route::get('showbydisease','PopulationController@ShowByDisease')->name('showbydisease');
Route::get('showbydisease_xls','PopulationController@ShowByDisease_Export')->name('export_total_disease');
//Route::get('showbydisease/{disease_code}/{year}','PopulationController@ShowByDisease')->where(['disease_code' => '[0-9]+','year' => '[0-9]+'])->name('export_total_disease');
/* export xls From disease name and year */
Route::get('export-csv', 'ExportController@export_by_disease')->name('export.form');
Route::post('exportbydisease','ExportController@get_files_export_by_disease')->name('exportbydisease');
/* Generate Link to Download Files CSV */
Route::get('export-csv', 'ExportController@export_by_disease')->name('export.form');
/* Start Export Population */
Route::get('export-population', 'ExportController@population_main')->name('export-population.main');
Route::get('export-population/sector', 'ExportController@population_sector')->name('export-population.sector');
Route::get('export-population/area', 'ExportController@population_area')->name('export-population.area');
Route::get('export-population/province', 'ExportController@population_province')->name('export-population.province');
Route::get('export-population/municipality', 'ExportController@population_municipality')->name('export-population.municipality');
Route::get('export-population/sex-age-province', 'ExportController@population_sex_age_province')->name('export-population.sex-age-province');
Route::get('export-population/sex-age-municipality', 'ExportController@population_sex_age_municipality')->name('export-population.sex-age-municipality');

Route::post('post_population_sector','ExportController@post_population_sector')->name('post_population_sector');
Route::post('post_population_area','ExportController@post_population_area')->name('post_population_area');
Route::post('post_population_province','ExportController@post_population_province')->name('post_population_province');
Route::post('post_population_municipality','ExportController@post_population_municipality')->name('post_population_municipality');
Route::post('post_population_sex_age_province','ExportController@post_population_sex_age_province')->name('post_sex-age-province');
Route::post('post_population_sex_age_municipality','ExportController@post_population_sex_age_municipality')->name('post_sex-age-municipality');
/* End Export Population */

/* Report */
Route::get('/report', function() {
	return view('frontend.report');
})->name('report');
/* Top 10 Disease Patient */
Route::get('/top10DsPt', 'WeekReportController@top10DsPt')->name('top10DsPt');
/* ******** backend ******** */
Route::get('/backend', function() {
	return view('backend/index');
});
