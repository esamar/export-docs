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

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/sample', 'SampleController@index')->name('sample');

Route::get('/sample/edit/{id_sample}/{option}', 'SampleController@edit')->name('sample.edit');

// Route::get('/users', 'UsersController@index')->name('user');

Route::get('/configs/{option}', 'ConfigController@index')->name('config.index');

Route::get('/configs/get/{nivel}', 'ConfigController@show')->name('config.show');




Route::get('seeds/{id_especialista}', function ( $id_especialista ) {

    return view('seeds')->with('id_especialista' , $id_especialista );

})->name('seeds');

Route::get('/import', function() {
	return view('import');
});

Route::post('import-directory-excel-dir' , 'PersonController@importExcel')->name('import.directory.excel.dir');

Route::post('import-directory-excel-doc' , 'PersonController@importExcelDocente')->name('import.directory.excel.doc');

Route::post('import-directory-excel-ppff' , 'PersonController@importExcelPPFF')->name('import.directory.excel.ppff');

Route::get('user-list-pdf' , 'PersonController@ExportPdf')->name('users.pdf');

Route::get('user-list-excel' , 'PersonController@ExportExcel')->name('users.excel');

// Route::get('import-to-director' , 'PersonController@importTableDirector')->name('import.director');

Route::get('import-to-director/{id_temporal}/{id_especialista}', array('as' => 'import-to-director', 'uses' => 'PersonController@importTableDirector'));

Route::get('import-to-docente/{id_temporal}/{id_especialista}', array('as' => 'import-to-docente', 'uses' => 'PersonController@importTableDocente'));

Route::get('import-to-ppff/{id_temporal}/{id_especialista}', array('as' => 'import-to-ppff', 'uses' => 'PersonController@importTablePPFF'));