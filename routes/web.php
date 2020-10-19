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


Route::post('check_otp', 'OtpController@check_otp')->name('check_otp');
Route::get('verify_otp', 'OtpController@verify_otp')->name('verify_otp');
Route::post('test_otp', 'OtpController@test_otp')->name('test_otp');
Route::get('resend_otp', 'OtpController@resend_otp')->name('resend_otp');

Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');


    Route::get('/', 'HomeController@home')->middleware('auth');





    Route::post('get-user', 'UserController@getUser');
    Route::get('raaohs', 'RaaohsController@index');
    Route::get('raaods', 'RaaodsController@getRaaods');
    Route::get('raaods-ledger', 'RaaodsController@getRaaodsLedger');

    Route::get('funds', 'FundController@index');
    Route::get('functions', 'FunctionController@index');
    Route::get('appropriation-types', 'AppropriationTypeController@index');
    Route::post('change-password', 'UserController@changePassword');
    Route::post('save_schedule', 'ScheduleController@save_schedule')->name('save_schedule');


