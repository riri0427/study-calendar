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

Route::get('/', 'CalendarController@top')->name('calendar.top');
Route::get('/calendar', 'CalendarController@top')->name('calendar.top');
Route::post('/calendar/prev', 'CalendarController@top')->name('calendar.prev');
Route::post('/calendar/next', 'CalendarController@top')->name('calendar.next');

