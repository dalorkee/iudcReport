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
Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/', 'HomeController@index')->name('index');
/* dashboard */
Route::get('/dashboard', 'DashboardController@index')->name('dashboard')->middleware('auth');
Route::get('/dbd', 'DashboardController@index')->name('dbd')->middleware('auth');
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
Route::prefix('export-population')->group(function () {
/* Start Export Population */
Route::get('/', 'ExportController@population_main')->name('export-population.main');
Route::get('sector', 'ExportController@population_sector')->name('export-population.sector');
Route::get('area', 'ExportController@population_area')->name('export-population.area');
Route::get('province', 'ExportController@population_province')->name('export-population.province');
Route::get('municipality', 'ExportController@population_municipality')->name('export-population.municipality');
Route::get('sex-age-province', 'ExportController@population_sex_age_province')->name('export-population.sex-age-province');
Route::get('sex-age-municipality', 'ExportController@population_sex_age_municipality')->name('export-population.sex-age-municipality');
});
//Post Action
Route::post('post_population_sector','ExportController@post_population_sector')->name('post_population_sector');
Route::post('post_population_area','ExportController@post_population_area')->name('post_population_area');
Route::post('post_population_province','ExportController@post_population_province')->name('post_population_province');
Route::post('post_population_municipality','ExportController@post_population_municipality')->name('post_population_municipality');
Route::post('post_population_sex_age_province','ExportController@post_population_sex_age_province')->name('post_sex-age-province');
Route::post('post_population_sex_age_municipality','ExportController@post_population_sex_age_municipality')->name('post_sex-age-municipality');
/* End Export Population */
Route::prefix('export-patient')->group(function () {
/* Start Export Patient Data */
//Load Form
Route::get('/','ExportPatientController@patient_main')->name('export-patient-data.main');
Route::get('sick-death-month', 'ExportPatientController@patient_sick_death_by_month')->name('export-patient.sick-death-month');
Route::get('sick-death-ratio', 'ExportPatientController@patient_sick_death_ratio')->name('export-patient.sick-death-ratio');
Route::get('sick-weekly', 'ExportPatientController@patient_sick_weekly')->name('export-patient.sick-weekly');
Route::get('sick-by-age', 'ExportPatientController@patient_sick_by_age')->name('export-patient.sick-by-age');
Route::get('death-by-age', 'ExportPatientController@patient_death_by_age')->name('export-patient.death-by-age');
Route::get('sick-death-by-nation', 'ExportPatientController@patient_sick_death_by_nation')->name('export-patient.sick-death-by-nation');
Route::get('sick-by-occupation', 'ExportPatientController@patient_sick_by_occupation')->name('export-patient.sick-by-occupation');
Route::get('sick-by-sex', 'ExportPatientController@patient_sick_by_sex')->name('export-patient.sick-by-sex');
//Post Action
// API call Data
Route::get('export-xls-patient-sick-death-by-month','ExportPatientController@xls_patient_sick_death_by_month')->name('xls_patient_sick_death_by_month');
Route::get('export-xls-patient-sick-death-ratio','ExportPatientController@xls_patient_sick_death_ratio')->name('xls_patient_sick_death_ratio');
Route::get('export-xls-sick-weekly','ExportPatientController@xls_patient_sick_weekly')->name('xls_patient_sick_weekly');
Route::get('export-xls-sick-by-age','ExportPatientController@xls_patient_sick_by_age')->name('xls_patient_sick_by_age');
Route::get('export-xls-death-by-age','ExportPatientController@xls_patient_death_by_age')->name('xls_patient_death_by_age');
Route::get('export-xls-sick-death-by-nation','ExportPatientController@xls_patient_sick_death_by_nation')->name('xls_patient_sick_death_by_nation');
Route::get('export-xls-sick-by-occupation','ExportPatientController@xls_patient_sick_by_occupation')->name('xls_patient_sick_by_occupation');
Route::get('export-xls-sick-by-sex','ExportPatientController@xls_patient_sick_by_sex')->name('xls_patient_sick_by_sex');
});
/* End Export Patient Data */
/* Report */
Route::get('/report', function() {
	return view('frontend.report');
})->name('report')->middleware('auth');
/* Top 10 Disease Patient */
Route::get('/top10DsPt', 'WeekReportController@index')->name('top10DsPt')->middleware('auth');
/* One page */
Route::get('/onePage', 'OnePageController@index')->name('onePage')->middleware('auth');
/* ******** backend ******** */
Route::get('/backend', function() {
	return view('backend/index');
});
