<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SpaController;
use App\Http\Controllers\PcgMonitoringController;
use App\Http\Controllers\PcgReportController;
use App\Http\Controllers\LoginController;
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

// Route::get('/', function () {
//     return view('welcome');
// });
Route::view('/sample/test', 'layouts.sample');

Route::get('/{any?}', [SpaController::class, 'index'])->where('any', '.*');

Route::get('/users', [UserController::class, 'index']);

Route::post('/monitoring_excel', [PcgMonitoringController::class, 'monitoring_excel']);

Route::post('/report_excel', [PcgReportController::class, 'report_excel']);

// Route::post('/getUserData', [LoginController::class, 'getUserData']);
Route::post('/updateEmployees', [LoginController::class, 'updateEmployees']);