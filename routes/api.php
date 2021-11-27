<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\NewsController;
use App\Http\Controllers\Api\FakultasController;
use App\Http\Controllers\Api\JurusanController;
use App\Http\Controllers\Api\StreamingController;
use App\Http\Controllers\Api\LandingController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

$router->get('/', [LandingController::class, 'index']);

$router->post('/login', [AuthController::class, 'authenticate']);
$router->get('/cek-user', [AuthController::class, 'decodetoken']);

// $router->group(['prefix' => 'news'], function() use ($router) {
//     $router->get('/', [NewsController::class, 'index']);
// });
Route::group(['prefix' => 'admin', 'middleware' => ['user']], function () {
    Route::resource('news', NewsController::class);
    Route::resource('fakultas', FakultasController::class);
    Route::resource('jurusan', JurusanController::class);
    Route::resource('streaming', StreamingController::class);
});
