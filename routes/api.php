<?php

use App\Http\Controllers\ExpensesController;
use App\Http\Controllers\IncomesController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return response()->json([
        'message' => "it works!"
    ]);
});

Route::group(['prefix' => 'receitas', 'controller' => IncomesController::class], function () {
    Route::get('', 'get');
    Route::post('', 'create');

    Route::group(['prefix' => '{id}', 'where' => ['id', "/^[0-9]*$/"]], function () {
        Route::get('', 'find');
        Route::put('', 'update');
        Route::delete('', 'delete');
    });
});

Route::group(['prefix' => 'despesas', 'controller' => ExpensesController::class], function () {
    Route::get('', 'get');
    Route::post('', 'create');

    Route::group(['prefix' => '{id}', 'where' => ['id', "/^[0-9]*$/"]], function () {
        Route::get('', 'find');
        Route::put('', 'update');
        Route::delete('', 'delete');
    });
});
