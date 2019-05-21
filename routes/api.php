<?php

use Illuminate\Http\Request;

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


Route::group(['prefix' => 'auth', 'middleware' => 'cors'], function () {
   
    Route::post('/login', 'AuthController@login');
    
    Route::group(['middleware' => ['jwt.auth']], function(){
        Route::post('/refresh', 'AuthController@refresh');
        Route::post('/logout', 'AuthController@logout');
    });

});


Route::group(['prefix' => 'mapa_comissao', 'middleware' => 'cors'], function (){
    Route::group(['middleware' => ['jwt.auth']], function() {
        
        Route::post('/propostas/bydate', 'MapaComissaoControllerMaster@propostasByDate');
        
        Route::post('/sync', 'MapaComissaoControllerMaster@syncBaseByDate');
        
    });


});