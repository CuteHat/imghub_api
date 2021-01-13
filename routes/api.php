<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\AuthController;

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

Route::get("/image/search", 'App\Http\Controllers\ImageController@getImagesWithTag');
Route::apiResource("/image", ImageController::class);
Route::get("/tag/{imageId}", 'App\Http\Controllers\TagController@getImageTags');
Route::post("/tag", 'App\Http\Controllers\TagController@attachToImage');
Route::delete("/tag", 'App\Http\Controllers\TagController@detachFromImage');

Route::post("/register", 'App\Http\Controllers\AuthController@register');
Route::post("/login", 'App\Http\Controllers\AuthController@login');
Route::post("/logout", 'App\Http\Controllers\AuthController@logout');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
