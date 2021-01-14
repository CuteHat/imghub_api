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
Route::get("/image", 'App\Http\Controllers\ImageController@index');
Route::get("/image/{imageId}", 'App\Http\Controllers\ImageController@show');
Route::get("/tag/{imageId}", 'App\Http\Controllers\TagController@getImageTags');
Route::post("/register", 'App\Http\Controllers\AuthController@register');
Route::post("/login", 'App\Http\Controllers\AuthController@login');

Route::group([
    'middleware' => 'api',
    ],  function ($router) {
        Route::post("/image", 'App\Http\Controllers\ImageController@store');
        Route::put("/image/{imageId}", 'App\Http\Controllers\ImageController@update');
        Route::delete("/image/{imageId}", 'App\Http\Controllers\ImageController@destroy');
        Route::post("/logout", 'App\Http\Controllers\AuthController@logout');
        Route::post("/tag", 'App\Http\Controllers\TagController@attachToImage');
        Route::delete("/tag", 'App\Http\Controllers\TagController@detachFromImage');
});
