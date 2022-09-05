<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\PriceController;
use Illuminate\Http\Request;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);



// Protected routes
Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::post('/price', [PriceController::class, 'getPrice']);
    

    Route::post('/logout', [AuthController::class, 'logout']);
});
