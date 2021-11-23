<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\NewsController;
use App\Http\Controllers\Api\FakultasController;
use App\Http\Controllers\Api\JurusanController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/get-csrf', function () {
    echo csrf_token();
});

// Route::group(['prefix' => 'admin'], function () {
//     Route::group(['prefix' => 'news'], function () {
//         Route::get('/', [NewsController::class, 'index']);
//         Route::post('/', [NewsController::class, 'store']);
//     });
//     Route::resource('news', NewsController::class);
//     Route::resource('fakultas', FakultasController::class);
//     Route::resource('jurusan', JurusanController::class);
// });