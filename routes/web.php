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

Route::get('/', function ( ) {
    return view('welcome');
});

Route::get('inicio/{id_especialista}', function ( $id_especialista ) {

    return view('welcome')->with('id_especialista' , $id_especialista );

})->name('inicio');

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