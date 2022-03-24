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

Route::get('/', function () {
    return redirect('/login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/home-data', 'HomeController@indexData');
Route::post('/save-qr-hdf', 'HomeController@saveQrHealthDeclarationForm');

//RT PCR Logs
Route::get('/rt-pcr-logs','RtPcrLogsController@index');
Route::get('/get-all-rt-pcr-logs','RtPcrLogsController@getAllRtPcrLogs');
Route::post('/save-rt-pcr-logs','RtPcrLogsController@saveRtPcrLogs');
Route::post('/update-rt-pcr-logs','RtPcrLogsController@updateRtPcrLogs');

//Vaccine Logs
Route::get('/vaccine-logs','VaccineLogsController@index');
Route::get('/get-all-vaccine-logs','VaccineLogsController@getAllVaccineLogs');
Route::post('/save-vaccine-logs','VaccineLogsController@saveVaccineLogs');
Route::post('/update-vaccine-logs','VaccineLogsController@updateVaccineLogs');

//User Vaccination Details
Route::get('/get-user-vaccination-details','VaccineLogsController@getUserVaccinationDetails');
Route::post('/update-user-vaccination-details','VaccineLogsController@updateUserVaccinationDetails');

//Case Logs
Route::get('/case-logs','CaseLogsController@index');
Route::get('/get-all-case-logs','CaseLogsController@getAllCaseLogs');
Route::post('/save-case-logs','CaseLogsController@saveCaseLogs');
Route::post('/update-case-logs','CaseLogsController@updateCaseLogs');
Route::post('/remove-case-logs','CaseLogsController@removeCaseLogs');

Route::post('/save-new-case','CaseLogsController@saveNewCase');


Route::get('/get-all-users','CaseLogsController@getAllUsers');



