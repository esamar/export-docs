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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/import', function() {
	return view('import');
});

Route::post('import-directory-excel' , 'PersonController@importExcel')->name('import.directory.excel');

Route::get('user-list-pdf' , 'PersonController@ExportPdf')->name('users.pdf');

Route::get('user-list-excel' , 'PersonController@ExportExcel')->name('users.excel');