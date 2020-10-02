<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\CurrencyController;
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

Route::group(['middleware' => 'json.response'], function () {
    Route::post('/register', [ApiController::class, 'register']);
    Route::post('/login', [ApiController::class, 'login']);
    Route::get('/refresh', [ApiController::class, 'refresh']);
    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('/logout', [ApiController::class, 'logout']);
        Route::get('/rates', [CurrencyController::class, 'rates']);
    });
});

