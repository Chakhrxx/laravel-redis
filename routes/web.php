<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/api-docs', function () {
    return view('swagger');
});

Route::get('/', function () {
    return view('app');
});

Route::Resource('user', UserController::class);
Route::get('search-user', [UserController::class, 'search'])->name('user.search');
