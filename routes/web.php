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


Route::group(['middleware' => 'web'], function () {

  Route::get('/admin', 'Admin\AdminLoginController@adminlogin');
  Route::get('/admin/login', 'Admin\AdminLoginController@adminLogin')->name('admin-login-get');
  Route::post('/admin-login', 'Admin\AdminLoginController@login')->name('admin-login-post');

  Route::group(['prefix' => '/admin', 'middleware' => 'admin'], function () {
   Route::get('/dashboard', 'Admin\AdminLoginController@dashboard')->name('admin-dashboard');
   Route::get('/admin-logout', 'Admin\AdminLoginController@logout')->name('admin-logout');
   Route::get('/staff_list', 'Admin\StaffController@index')->name('staff-list');
   Route::get('/job_list', 'Admin\JobController@index')->name('job-list');
   Route::get('/admin-job-view/{id}', [
    'as' => 'admin.adminjob.view',
    'uses' => 'Admin\JobController@view'
  ]);

        ## Role Routes
   Route::get('/role-list', 'Admin\RoleController@index')->name('role-list');
   Route::resource('ajaxRole','Admin\RoleController');
   Route::get('/role/edit/{id}', 'Admin\RoleController@edit' )->name('role-edit');
   Route::post('/role/update', 'Admin\RoleController@update' )->name('role-update');
   Route::get('/role/delete/{id}', 'Admin\RoleController@delete' )->name('role-delete');
   Route::get('/role/add', 'Admin\RoleController@add' )->name('role-add');
   Route::post('/role/insert', 'Admin\RoleController@add' )->name('role-insert');

   Route::post('/get_stafflist', 'Admin\StaffController@getStaffList')->name('get-stafflist');	 	
   Route::get('/admin_stafflist', 'Admin\AdminStaffController@index')->name('admin-stafflist');
   
   Route::post('admin_staff_save', [
     'as' => 'admin.adminstaff.save',
     'uses' => 'Admin\AdminStaffController@saveStaff'
   ]);

   Route::get('/admin-staff-edit/{id}', [
    'as' => 'admin.adminstaff.edit',
    'uses' => 'Admin\AdminStaffController@edit'
  ]);
   Route::get('/admin-staff-add', [
    'as' => 'admin.adminstaff.add',
    'uses' => 'Admin\AdminStaffController@add'
  ]);

   Route::get('/admin-staff-delete/{id}', [
    'as' => 'admin.adminstaff.delete',
    'uses' => 'Admin\AdminStaffController@delete'
  ]);

   Route::get('/admin-staff-profile', [
    'as' => 'admin.adminstaff.profile',
    'uses' => 'Admin\AdminStaffController@profile'
  ]);

   Route::get('/staff-edit/{id}', [
    'as' => 'admin.staff.edit',
    'uses' => 'Admin\StaffController@edit'
  ]);

   Route::post('staff_save', [
    'as' => 'admin.staff.save',
    'uses' => 'Admin\StaffController@saveStaff'
  ]);

        // Docket Routes
   Route::get('/docket', 'Admin\DocketController@dockets')->name('docket-list');
   Route::get('/docket-print/{id}', 'Admin\DocketController@docketPrint')->name('docket-print');
   Route::post('/get-docket', 'Admin\DocketController@getDockets')->name('get-dockets');
   Route::post('/get-job-details', 'Admin\DocketController@getJobDetailsData')->name('get-job-details');
   Route::post('/save-job-details', 'Admin\DocketController@saveJobDetails')->name('save-job-details');

   Route::post('/get-risk-asmt-details', 'Admin\DocketController@getRiskAsmtData')->name('get-risk-asmt-details');
   Route::post('/save-risk-asmt', 'Admin\DocketController@saveRiskAsmt')->name('save-risk-asmt');

   Route::post('/get-site-details', 'Admin\DocketController@getSiteData')->name('get-site-details');
   Route::post('/save-site', 'Admin\DocketController@saveSite')->name('save-site');

   Route::post('/get-protection-details', 'Admin\DocketController@getProtectionData')->name('get-protection-details');
   Route::post('/save-protection', 'Admin\DocketController@saveProtection')->name('save-protection');

   Route::post('/get-driving-details', 'Admin\DocketController@getDrivingData')->name('get-driving-details');
   Route::post('/save-driving', 'Admin\DocketController@saveDriving')->name('save-driving');

   Route::post('/get-traffic-details', 'Admin\DocketController@getTrafficData')->name('get-traffic-details');
   Route::post('/save-traffic', 'Admin\DocketController@saveTraffic')->name('save-traffic');

   Route::post('/get-plant-details', 'Admin\DocketController@getPlantData')->name('get-plant-details');
   Route::post('/save-plant', 'Admin\DocketController@savePlant')->name('save-plant');

   Route::post('/get-sign-details', 'Admin\DocketController@getSignData')->name('get-sign-details');
   Route::get('remove-sign/{id}', array('as'=> 'remove-sign', 'uses' => 'Admin\DocketController@removeSign') );

        // Crane check page
   Route::get('crane-check', array('as'=> 'crane-check', 'uses' => 'Admin\CraneCheckController@index') );        
   Route::get('/get-crane-check/{id}', array('as'=> 'get-crane-check', 'uses' => 'Admin\CraneCheckController@getCranecheckData') );
   Route::get('remove-sign-crane/{id}', array('as'=> 'remove-sign-crane', 'uses' => 'Admin\CraneCheckController@removeSign') );
   Route::get('remove-issue-crane/{id}', array('as'=> 'remove-issue-crane', 'uses' => 'Admin\CraneCheckController@removeIssue') );
   Route::post('get-crane-check-by-dates', 'Admin\CraneCheckController@getCraneChecksByDates')->name('get-crane-check-by-dates');

        // Crane fuel record
   Route::get('/crane-fuel-record', array('as'=> 'crane-fuel-record', 'uses' => 'Admin\CraneFuelController@index') );

        // Activity Log
   Route::get('activity-log', 'Admin\ActivityLogController@index')->name('activity-log');

   Route::get('list-Log', 'Admin\ActivityLogController@listingLog')->name('ajaxlog');
   Route::post('activity-log-filter', 'Admin\ActivityLogController@listingLog')->name('activity-log-filter');
 });
Route::resource('ajaxstaff','Admin\StaffController');
Route::resource('ajaxjob','Admin\JobController');
Route::resource('ajaxAdminStaff','Admin\AdminStaffController');
Route::resource('ajaxfuel','Admin\CraneFuelController');
    // Route::resource('ajaxlog','Admin\ActivityLogController');
});


