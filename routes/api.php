<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PcgMonitoringController;
use App\Http\Controllers\PcgReportController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PcgChartController;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\CheckedController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CommonTermsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// SELECT * FROM dbo.Employees WHERE DepartmentCode = '147' AND SectionCode = '00480' AND TeamCode = '00574'

// Route::prefix('wpc_size_master')->group(function () { Route::post('/insert', [WpcSizeController::class, 'insert']); Route::get('/getData', [WpcSizeController::class, 'getData']); Route::post('/update', [WpcSizeController::class, 'update']); Route::post('/delete_size', [WpcSizeController::class, 'delete_size']); });
Route::post('/insertUserData', [LoginController::class, 'insertUserData']);
Route::post('/updateUserData', [LoginController::class, 'updateUserData']);
Route::post('/getUserData', [LoginController::class, 'getUserData']);

Route::post('/indexMonitoring', [PcgMonitoringController::class, 'indexMonitoring']);
Route::post('/getPCGChart', [PcgMonitoringController::class, 'getPCGChart']);
Route::post('/getFilename', [PcgMonitoringController::class, 'getFilename']);
Route::get('/getNotification', [PcgMonitoringController::class, 'getNotification']);
Route::post('/add_pcg', [PcgMonitoringController::class, 'add_pcg']);
Route::post('/edit_pcg', [PcgMonitoringController::class, 'edit_pcg']);
Route::post('/delete_pcg', [PcgMonitoringController::class, 'delete_pcg']);
Route::post('/category', [PcgMonitoringController::class, 'category']);
Route::post('/code', [PcgMonitoringController::class, 'code']);
Route::post('/edit_pcg_attachment', [PcgMonitoringController::class, 'edit_pcg_attachment']);

Route::post('/reset_template_status', [PcgMonitoringController::class, 'reset_template_status']);
Route::post('/check_pcg', [PcgMonitoringController::class, 'check_pcg']);
Route::post('/approve_pcg', [PcgMonitoringController::class, 'approve_pcg']);
Route::post('/disapprove_pcg', [PcgMonitoringController::class, 'disapprove_pcg']);

Route::post('/delete_report_pcg', [PcgReportController::class, 'delete_report_pcg']);
Route::post('/insertReport', [PcgReportController::class, 'insertReport']);
Route::get('/getReport', [PcgReportController::class, 'getReport']);
Route::post('/getJapanEmployees', [PcgReportController::class, 'getJapanEmployees']);

Route::post('/getChart', [PcgChartController::class, 'getChart']);

Route::post('/openPDF', [PcgMonitoringController::class, 'openPDF']);

Route::post('/getFilename2', [PcgReportController::class, 'getFilename2']);

Route::get('/get_categories', [MasterController::class, 'get_categories']);
Route::post('/save_category', [MasterController::class, 'save_category']);
Route::post('/update_category', [MasterController::class, 'update_category']);
Route::post('/delete_category', [MasterController::class, 'delete_category']);

Route::get('/get_methods', [MasterController::class, 'get_methods']);
Route::post('/save_method', [MasterController::class, 'save_method']);
Route::post('/update_method', [MasterController::class, 'update_method']);
Route::post('/delete_method', [MasterController::class, 'delete_method']);

Route::get('/get_pcgtypes', [MasterController::class, 'get_pcgtypes']);
Route::post('/save_pcgtype', [MasterController::class, 'save_pcgtype']);
Route::post('/update_pcgtype', [MasterController::class, 'update_pcgtype']);
Route::post('/delete_pcgtype', [MasterController::class, 'delete_pcgtype']);

Route::get('/get_teams', [MasterController::class, 'get_teams']);
Route::post('/save_team', [MasterController::class, 'save_team']);
Route::post('/update_team', [MasterController::class, 'update_team']);
Route::post('/delete_team', [MasterController::class, 'delete_team']);

Route::get('/getUsers', [MasterController::class, 'getUsers']);
Route::post('/updateUsers', [MasterController::class, 'updateUsers']);
Route::post('/deleteUser', [MasterController::class, 'deleteUser']);
Route::post('/addUser', [MasterController::class, 'addUser']);

Route::get('/get_actions', [MasterController::class, 'get_actions']);
Route::post('/save_action', [MasterController::class, 'save_action']);
Route::post('/update_action', [MasterController::class, 'update_action']);
Route::post('/delete_action', [MasterController::class, 'delete_action']);

Route::get('/get_versions', [MasterController::class, 'get_versions']);

Route::post('/insert_test_carte', [MasterController::class, 'insert_test_carte']);

Route::get('/getCheckedTemplates', [CheckedController::class, 'getCheckedTemplates']);
Route::get('/getApprovedTemplates', [CheckedController::class, 'getApprovedTemplates']);
Route::get('/getForApprovalTemplates', [CheckedController::class, 'getForApprovalTemplates']);
Route::post('/approveTemplates', [CheckedController::class, 'approveTemplates']);

Route::post('/get_print', [CartController::class, 'get_print']);
Route::post('/delete_print', [CartController::class, 'delete_print']);
Route::post('/delete_multiple_print', [CartController::class, 'delete_multiple_print']);
Route::post('/insert_print', [CartController::class, 'insert_print']);
Route::post('/update_print', [CartController::class, 'update_print']);
Route::post('/update_cart_action', [CartController::class, 'update_cart_action']);
Route::post('/update_cart_pages', [CartController::class, 'update_cart_pages']);
Route::post('/update_cart_judgement', [CartController::class, 'update_cart_judgement']);

Route::post('/save_common_term', [CommonTermsController::class, 'save_common_term']);
Route::post('/update_common_term', [CommonTermsController::class, 'update_common_term']);
Route::get('/get_common_terms', [CommonTermsController::class, 'get_common_terms']);
Route::post('/delete_common_term', [CommonTermsController::class, 'delete_common_term']);

