<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\VisiMisiController;
use App\Http\Controllers\Api\StrukturOrganisasiController;
use App\Http\Controllers\Api\ProgramKerjaController;
use App\Http\Controllers\Api\OrganisasiController;
use App\Http\Controllers\Api\NewsController;
use App\Http\Controllers\Api\KaryaUPNController;
use App\Http\Controllers\Api\CarierCenterController;
use App\Http\Controllers\Api\BeasiswaController;
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

// Route::middleware(['cors'])->group(function () {
    Route::get('/landing', [LandingController::class, 'index']);
    Route::get('/strukturorganisasi', [StrukturOrganisasiController::class, 'getStruktur']);
    Route::get('/organisasi', [OrganisasiController::class, 'getOrganisasid']);
    Route::get('/beritautama', [NewsController::class, 'getNewsTiga']);
    Route::get('/beritaall', [NewsController::class, 'getNewsDelapan']);
    Route::get('/cek-user', [AuthController::class, 'decodetoken']);

    Route::post('/login', [AuthController::class, 'authenticate']);

    // $router->group(['prefix' => 'news'], function() use ($router) {
    //     $router->get('/', [NewsController::class, 'index']);
    // });
    Route::group(['prefix' => 'admin', 'middleware' => ['user']], function () {
        // profil
        Route::group(['prefix' => 'visimisi'], function ($router) {
            Route::get('/', [VisiMisiController::class, 'show']);
            Route::post('/update/{id}', [VisiMisiController::class, 'update']);
        });
        Route::group(['prefix' => 'strukturorganisasi'], function ($router) {
            Route::get('/', [StrukturOrganisasiController::class, 'show']);
            Route::post('/update/{id}', [StrukturOrganisasiController::class, 'update']);
        });
        Route::resource('programkerja', ProgramKerjaController::class);
        Route::resource('organisasi', OrganisasiController::class);

        // publikasi
        Route::resource('berita', NewsController::class);
        Route::resource('karyaUPN', KaryaUPNController::class);

        // informasi
        Route::resource('cariercenter', CarierCenterController::class);
        Route::resource('beasiswa', BeasiswaController::class);

        Route::resource('fakultas', FakultasController::class);
        Route::resource('jurusan', JurusanController::class);
        Route::resource('streaming', StreamingController::class);
    });
// });
