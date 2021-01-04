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

Route::name('public.')->middleware(['active', 'check.session'])->group(function() {
    Route::get('', 'PublicController@index')->name('index');
    Route::get('aboutUs', 'PublicController@aboutUs')->name('aboutUs');
    Route::get('contact', 'PublicController@contact')->name('contact');

    // ROUTE TO SEND FEEDBACK
    Route::post('sendFeedback', 'PublicController@sendFeedback')->name('sendFeedback')->middleware('auth');

    // ROUTE FOR PUBLIC - BOOKS
    Route::get('books', 'PublicController@books')->name('books');
    Route::get('books/{id}/detail', 'PublicController@bookDetail')->name('bookDetail');

    // ROUTE FOR PUBLIC - EBOOKS
    Route::get('ebooks', 'PublicController@ebooks')->name('ebooks')->middleware('auth');
    Route::get('ebooks/{id}/detail', 'PublicController@ebookDetail')->name('ebookDetail')->middleware('auth');
    Route::get('ebooks/{id}', 'PublicController@ebookRead')->name('ebookRead')->middleware('auth');

    // ROUTE FOR SEARCH BOOKS
    Route::get('search', 'PublicController@search')->name('search');

    // ROUTE FOR PUBLIC
    Route::get('history', 'PublicController@history')->name('history')->middleware('auth');
    Route::get('changePassword', 'Auth\ResetPasswordController@showChangePasswordForm')->name('changepassword')->middleware('auth');
    Route::post('updatePassword', 'Auth\ResetPasswordController@postChangePassword')->name('updatepassword')->middleware('auth');
});

Auth::routes();
Route::get('auth', 'Auth\LoginController@adminLogin')->name('adminLogin');

Route::any('register', function() {
    return redirect()->back();
});

// ROUTE FOR ADMIN ONLY
Route::name('admin.')->prefix('admin')->middleware(['auth', 'admin', 'active', 'check.session'])->group(function() {
    Route::get('dashboard', 'AdminController@dashboard')->name('dashboard');
    Route::get('profile', 'AdminController@profile')->name('profile');
    Route::put('updateProfile', 'AdminController@updateProfile')->name('updateProfile');
    Route::get('changePassword', 'AdminController@changePassword')->name('changePassword');
    Route::post('updatePassword', 'AdminController@updatePassword')->name('updatePassword');
    Route::get('feedback', 'AdminController@feedback')->name('feedback');

    // Categories
    Route::resource('categories', 'CategoryController');
    Route::post('categories/deleteAllSelected', 'CategoryController@deleteAllSelected')->name('categories.deleteAllSelected');

    // Authors
    Route::resource('authors', 'AuthorController');
    Route::post('authors/deleteAllSelected', 'AuthorController@deleteAllSelected')->name('authors.deleteAllSelected');

    // Publishers
    Route::resource('publishers', 'PublisherController');
    Route::post('publishers/deleteAllSelected', 'PublisherController@deleteAllSelected')->name('publishers.deleteAllSelected');

    // Racks
    Route::resource('racks', 'RackController');
    Route::post('racks/deleteAllSelected', 'RackController@deleteAllSelected')->name('racks.deleteAllSelected');

    // Roles
    Route::resource('roles', 'RoleController');
    Route::post('roles/deleteAllSelected', 'RoleController@deleteAllSelected')->name('roles.deleteAllSelected');

    // Users
    Route::resource('users', 'UserController');
    Route::post('users/deleteAllSelected', 'UserController@deleteAllSelected')->name('users.deleteAllSelected');
    Route::get('users/{id}/changePassword', 'UserController@changePassword')->name('users.changePassword');
    Route::post('users/{id}/postChangePassword', 'UserController@postChangePassword')->name('users.postChangePassword');

    // Items
    Route::get('items/books', 'ItemController@indexBook')->name('items.books.index');
    Route::get('items/ebooks', 'ItemController@indexEbook')->name('items.ebooks.index');
    Route::delete('items/{id}', 'ItemController@destroy')->name('items.destroy');
    Route::post('items/deleteAllSelected', 'ItemController@deleteAllSelected')->name('items.deleteAllSelected');

    // Item - Lost Books
    Route::get('items/lostBooks', 'ItemController@indexLostBook')->name('items.lostBooks.index');
    Route::get('items/lostBooks/{id}/edit', 'ItemController@editLostBook')->name('items.lostBooks.edit');
    Route::put('items/lostBooks', 'ItemController@updateLostBook')->name('items.lostBooks.update');

    // Items - Books
    Route::get('items/books/create', 'ItemController@bookCreate')->name('items.books.create');
    Route::post('items/books', 'ItemController@bookStore')->name('items.books.store');
    Route::get('items/books/{id}/edit', 'ItemController@bookEdit')->name('items.books.edit');
    Route::put('items/books/{id}', 'ItemController@bookUpdate')->name('items.books.update');
    Route::get('items/books/{id}', 'ItemController@bookDetail')->name('items.books.detail');

    // Items - Ebooks
    Route::get('items/ebooks/create', 'ItemController@ebookCreate')->name('items.ebooks.create');
    Route::post('items/ebooks', 'ItemController@ebookStore')->name('items.ebooks.store');
    Route::get('items/ebooks/{id}/edit', 'ItemController@ebookEdit')->name('items.ebooks.edit');
    Route::put('items/ebooks/{id}', 'ItemController@ebookUpdate')->name('items.ebooks.update');
    Route::get('items/ebooks/{id}', 'ItemController@ebookDetail')->name('items.ebooks.detail');

    // Issues
    Route::get('issues/fetchUser', 'IssueController@fetchUser')->name('issues.fetchUser');
    Route::get('issues/fetchBook', 'IssueController@fetchBook')->name('issues.fetchBook');
    Route::get('issues/penaltySetting', 'IssueController@penaltySetting')->name('issues.penaltySetting');
    Route::put('issues/penaltyUpdate', 'IssueController@penaltyUpdate')->name('issues.penaltyUpdate');
    Route::get('issues/borrowSetting', 'IssueController@borrowSetting')->name('issues.borrowSetting');
    Route::get('issues/fetchRule', 'IssueController@fetchRule')->name('issues.fetchRule');
    Route::put('issues/borrowUpdate', 'IssueController@borrowUpdate')->name('issues.borrowUpdate');

    // Issues - Borrows
    Route::get('issues/borrows', 'IssueController@indexBorrow')->name('issues.borrows.index');
    Route::get('issues/borrows/create', 'IssueController@create')->name('issues.borrows.create');
    Route::post('issues/borrows', 'IssueController@store')->name('issues.borrows.store');
    Route::put('issues/borrows/{id}/renew', 'IssueController@renew')->name('issues.borrows.renew');
    Route::put('issues/borrows/{id}/return', 'IssueController@return')->name('issues.borrows.return');
    Route::put('issues/borrows/{id}/lost', 'IssueController@lost')->name('issues.borrows.lost');

    // Issues - Returns
    Route::get('issues/returns', 'IssueController@indexReturn')->name('issues.returns.index');
});
