<?php

use Illuminate\Http\Request;
use App\Http\Controllers\SMIController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('updateRange', 'SMIController@updateRange')->name('updateRange');

// Route::post('setState', 'SMIController@setState')->name('setState');

// Route::post('setState', array('middleware' => 'cors', 'uses' => 'SMIController@setState'));

Route::match(['options', 'post'], 'setState', function (Request $request ) {
    // This will work with the middleware shown in the accepted answer

	$setEstado = new SMIController;

	return $setEstado->setState($request);

	// Route::post('setState', 'SMIController@setState')->name('setState');


})->middleware('cors');