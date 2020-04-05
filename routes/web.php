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



Auth::routes(['register' => false]);

Route::get('/', 'HomeController@index')->name('home');

Route::get('/contest/contestant/{id}', 'HomeController@singleContest')->name('single-contest');
Route::post('/vote', 'PaymentController@vote')->name('vote');


Route::group(['middleware' => ['auth']], function () {

    Route::get('/dashboard', 'ContestController@index')->name('dashboard');

    // CONTEST
    Route::get('/create/contest', 'ContestController@create')->name('create-contest');
    Route::get('/edit/contest/{contest}-{slug}', 'ContestController@edit')->name('edit-contest');
    Route::get('/dashboard/single-contest/{single}-{slug}', 'ContestController@show')->name('show-contest');
    Route::post('/add/contest', 'ContestController@store')->name('add-contest');
    Route::post('/update/contest/{id}', 'ContestController@update')->name('update-contest');
    Route::get('/delete/contest/{id}', 'ContestController@destroy')->name('delete-contest');

    // CONTESTANTS
    Route::get('/create/contestant', 'ContestantController@create')->name('create-contestant');
    Route::get('/edit/contestant/{id}', 'ContestantController@edit')->name('edit-contestant');
    Route::post('/add/contestant', 'ContestantController@store')->name('add-contestant');
    Route::post('/update/contestant/{id}', 'ContestantController@update')->name('update-contestant');
    Route::get('/delete/contestant/{id}', 'ContestantController@destroy')->name('delete-contestant');
});

