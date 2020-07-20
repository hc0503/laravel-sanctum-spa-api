<?php

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

Route::get('/token/require', function () {
    return response()->json([
        'success' => false,
        'error' => 'Your credential token is invalid.'
    ]);
})->name('token_require');

Route::post('/token', 'API\AuthController@getToken');
Route::post('/register', 'API\AuthController@register');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::put('/profile/{user}', 'API\AuthController@updateProfile');
    Route::get('/profile/{user}', 'API\AuthController@showProfile');
    
});