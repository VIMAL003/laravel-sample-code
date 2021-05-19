<?php

use Illuminate\Http\Request;

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

// Get job & crane & staff data
Route::get( 'get-job-data', 'APIController@getJobData' )->name('get-job-data');
Route::get( 'get-crane-data', 'APIController@getCraneData' )->name('get-crane-data');
Route::get( 'get-staff-data', 'APIController@getStaffData' )->name('get-staff-data');
Route::post( 'get-docket-all', 'APIController@getDocketAll' )->name('get-docket-all-api');

// Get dynamic table fields for docket
Route::get( 'get-dynamic-fields-docket', 'APIController@getDynamicFieldDocket' )->name('get-dynamic-fields-docket');

// Get dynamic table fields for crane check
Route::get( 'get-dynamic-fields-crane-check', 'APIController@getDynamicFieldCraneCheck' )->name('get-dynamic-fields-crane-check');

// DOCKET ROUTES

## Job Details
Route::post('save-job-details', 'Admin\DocketController@saveJobDetails')->name('save-job-details-api');
Route::post('get-job-details', 'Admin\DocketController@getJobDetailsData')->name('get-job-details-api');

## Risk Assessment Routes
Route::post('get-risk-asmt-details', 'Admin\DocketController@getRiskAsmtData')->name('get-risk-asmt-details-api');
Route::post('save-risk-asmt', 'APIController@saveRiskAsmt')->name('save-risk-asmt-api');

## Site Routes
Route::post('get-site-details', 'Admin\DocketController@getSiteData')->name('get-site-details-api');
Route::post('save-site', 'APIController@saveSite')->name('save-site-api');

## Protection Routes
Route::post('get-protection-details', 'Admin\DocketController@getProtectionData')->name('get-protection-details-api');
Route::post('save-protection', 'Admin\DocketController@saveProtection')->name('save-protection-api');

## Driving Routes
Route::post('get-driving-details', 'Admin\DocketController@getDrivingData')->name('get-driving-details-api');
Route::post('save-driving', 'APIController@saveDriving')->name('save-driving-api');

## Traffic Routes
Route::post('get-traffic-details', 'Admin\DocketController@getTrafficData')->name('get-traffic-details');
Route::post('save-traffic', 'Admin\DocketController@saveTraffic')->name('save-traffic');

## Sign In
Route::post('get-job-sign-in', 'Admin\DocketController@getSignInData')->name('get-job-sign-in-api');
Route::post('save-job-sign-in', 'APIController@saveJobSignsIn')->name('save-job-sign-in-api');

## Sign Off
Route::post('get-job-sign-off', 'Admin\DocketController@getSignData')->name('get-job-sign-off-api');
Route::post('save-job-sign-off', 'APIController@saveJobSigns')->name('save-job-sign-off-api');

## Plant
Route::post('get-plant-details', 'Admin\DocketController@getPlantData')->name('get-plant-details-api');
Route::post('save-plant', 'APIController@savePlant')->name('save-plant-api');

// CRANE CHECK OPERATOR ROUTES
Route::post( 'save-crane-check', 'APIController@saveCraneCheck' )->name('save-crane-check-api');
Route::post( 'export-crane-check', 'APIController@exportCraneCheck' )->name('export-crane-check-api');
Route::post('get-crane-check-data', 'APIController@getCranecheckData' )->name('get-crane-check-api');
Route::get('get-crane-check-all', 'APIController@getCraneCheckAll' )->name('get-crane-check-all-api');

// ACTIVITY LOG ROUTES
Route::post( 'save-activity-log', 'APIController@saveActivityLog' )->name('save-activity-log-api');
Route::post( 'export-activity-log', 'APIController@exportActivityLog' )->name('export-activit-log-api');
Route::post( 'end-activity-timer', 'APIController@endActivityLog' )->name('end-activity-timer-api');
Route::post('get-activity-log', 'APIController@getActivityLog' )->name('get-activity-log-api');
Route::get('get-activity-log-all', 'APIController@getActivityLogAll' )->name('get-activity-log-all-api');

// CRANE FUEL RECORD ROUTES
Route::post( 'save-crane-fuel', 'APIController@saveCraneFuel' )->name('save-crane-fuel-api');
Route::post( 'export-crane-fuel', 'APIController@exportCraneFuelAll' )->name('export-crane-fuel-api');
Route::post( 'get-crane-fuel', 'APIController@getCraneFuelData' )->name('get-crane-fuel-api');

// GET DOCKET STATUS
Route::post( 'get-docket-status', 'APIController@getDocketStatus' )->name('get-docket-status-api');

// GET STAFF RFID
Route::get( 'get-rfid', 'APIController@getRFID' )->name('get-rfid-api');

// SAVE DOCKET OFFLINE
Route::post( 'save-docket-offline', 'APIController@saveDocketOffline' )->name('save-docket-offline-api');

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::post('get-staff-activity-log', 'APIController@getStaffActivityLog' )->name('get-staff-activity-log');
