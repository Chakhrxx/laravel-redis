<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['prefix' => 'v1/user/', [UserController::class]], function () {
    Route::get('', [UserController::class, 'index']);
    Route::get('{user}', [UserController::class, 'show']);
    Route::post('', [UserController::class, 'store']);
    Route::put('{user}', [UserController::class, 'update']);
    Route::delete('{user}', [UserController::class, 'destroy']);
});
