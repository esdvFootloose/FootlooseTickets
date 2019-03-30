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

Route::get('/', 'ReservationsController@create');
Route::post('/api/reservations', 'ReservationsController@store');
Route::get('/reservations', 'ReservationsController@index');
Route::get('/tickets', 'TicketsController@index');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
