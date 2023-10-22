<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::group(['middleware' => 'jwt.verify'], function ($router) {

    Route::group(['prefix' => 'auth'], function ($router) {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::post('/refresh', [AuthController::class, 'refresh'])->name('refresh');
        Route::post('/me', [AuthController::class, 'me'])->name('me');
    });
    Route::group(['prefix' => 'product'], function ($router) {

        Route::post('list', [ ProductController::class, 'list']);
        Route::post('new', [ ProductController::class, 'new']);
        Route::post('edit/{id}', [ ProductController::class, 'update']);
        Route::get('details/{id}', [ ProductController::class, 'view']);
        Route::patch('activate/{id}', [ ProductController::class, 'activate']);
        Route::patch('deactivate/{id}', [ ProductController::class, 'deactivate']);
        Route::delete('destroy/{id}', [ ProductController::class, 'destroy']);
    });
});