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

Route::get('/tickets', 'ReservationsController@create');
Route::get('/', 'USBReservationController@create');
Route::post('/api/reservations', 'ReservationsController@store');
Route::post('/api/reservations/movie', 'USBReservationController@store');
Route::get('/reservations', 'ReservationsController@index');
Route::get('/reservations/movie', 'USBReservationController@index');
Route::get('/tickets', 'TicketsController@index');
Route::get('reservations/download', 'ReservationsController@download');
Route::get('reservations/movie/download', 'USBReservationController@download');

Route::get('/reservations/newTikkie/{order_id}', 'ReservationsController@createNewTikkie');

Route::get('/reservations/movie/pickup/{id}', 'USBReservationController@pickup');

Auth::routes(['register' => false]);

Route::get('/home', 'HomeController@index')->name('home');
