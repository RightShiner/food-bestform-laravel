<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\MentalController;
use App\Http\Controllers\PhysicalController;
use App\Http\Controllers\PostsController;

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
Route::post('login', [UserController::class, 'login']);
Route::post('logout', [UserController::class, 'logout']);
Route::post('register', [UserController::class, 'register']);
Route::post('/user/info', [UserController::class, 'info']);

Route::group(['middleware' => 'auth:api'], function(){
	Route::post('user-details', [UserController::class, 'userDetails']);
});


Route::post('/mental/listall', [MentalController::class, 'listall']);
Route::post('/mental/question', [MentalController::class, 'question']);

Route::post('/physical/listall', [PhysicalController::class, 'listall']);
Route::post('/physical/question', [PhysicalController::class, 'question']);

Route::post('/posts/search', [PostsController::class, 'search']);
Route::post('/posts/order', [PostsController::class, 'order']);
Route::post('/posts/config', [PostsController::class, 'config']);
Route::post('/posts/schedule', [PostsController::class, 'schedule']);