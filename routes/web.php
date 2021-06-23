<?php

use Illuminate\Support\Facades\Auth;
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

Auth::routes();
Route::get('/', 'Auth\LoginController@adminLogin')->name('adminLogin');


// ROUTE FOR ADMIN ONLY
Route::name('admin.')->prefix('admin')->middleware(['auth', 'admin', 'active', 'check.session'])->group(function () {
    Route::get('dashboard', 'AdminController@dashboard')->name('dashboard');
    Route::get('profile', 'AdminController@profile')->name('profile');
    Route::put('updateProfile', 'AdminController@updateProfile')->name('updateProfile');

    // Categories
    Route::resource('company', 'CompanyController');

    // Products
    Route::resource('products', 'ProductController');

    // Report
    Route::resource('report', 'ReportController');
    Route::get('generate-pdf', 'ReportController@generatePDF')->name('pdf');;

    // Users
    Route::resource('users', 'UserController');
    Route::get('users/{id}/changePassword', 'UserController@changePassword')->name('users.changePassword');
    Route::post('users/{id}/postChangePassword', 'UserController@postChangePassword')->name('users.postChangePassword');
});
