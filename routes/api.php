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
use App\Http\Controllers\Api\NamaLogoController;
use App\Http\Controllers\Api\FooterController;
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

Route::get('/landing', [LandingController::class, 'index']);
Route::get('/strukturorganisasi', [StrukturOrganisasiController::class, 'getStruktur']);
Route::get('/namalogo', [NamaLogoController::class, 'getNamaLogo']);
Route::get('/footer', [FooterController::class, 'getFooter']);

Route::group(['prefix' => 'programkerja'], function () {
    Route::get('/', [ProgramKerjaController::class, 'getProgramKerja']);
    Route::get('/{id}', [ProgramKerjaController::class, 'getOne']);
});
Route::group(['prefix' => 'organisasi'], function () {
    Route::get('/', [OrganisasiController::class, 'getOrganisasi']);
    Route::get('/{id}', [OrganisasiController::class, 'getOne']);
});
Route::group(['prefix' => 'karyaupn'], function () {
    Route::get('/', [KaryaUPNController::class, 'getKaryaUPN']);
    Route::get('/{id}', [KaryaUPNController::class, 'getOne']);
});
Route::group(['prefix' => 'cariercenter'], function () {
    Route::get('/', [CarierCenterController::class, 'getCarierCenter']);
    Route::get('/{id}', [CarierCenterController::class, 'getOne']);
});
Route::group(['prefix' => 'beasiswa'], function () {
    Route::get('/', [BeasiswaController::class, 'getBeasiswa']);
    Route::get('/{id}', [BeasiswaController::class, 'getOne']);
});

// berita
Route::get('/beritautama', [NewsController::class, 'getNewsTiga']);
Route::get('/beritaall', [NewsController::class, 'getNewsDelapan']);
Route::get('/berita/{id}', [NewsController::class, 'getOne']);

Route::get('/cek-user', [AuthController::class, 'decodetoken']);
Route::post('/login', [AuthController::class, 'authenticate']);

// $router->group(['prefix' => 'news'], function() use ($router) {
//     $router->get('/', [NewsController::class, 'index']);
// });
Route::group(['prefix' => 'admin', 'middleware' => ['user']], function () {
    // profil
    Route::group(['prefix' => 'visimisi'], function () {
        Route::get('/', [VisiMisiController::class, 'show']);
        Route::post('/update/{id}', [VisiMisiController::class, 'update']);
    });
    Route::group(['prefix' => 'strukturorganisasi'], function () {
        Route::get('/', [StrukturOrganisasiController::class, 'show']);
        Route::post('/update/{id}', [StrukturOrganisasiController::class, 'update']);
    });
    Route::resource('programkerja', ProgramKerjaController::class);
    Route::resource('organisasi', OrganisasiController::class);

    // publikasi
    Route::resource('berita', NewsController::class);
    Route::resource('karyaupn', KaryaUPNController::class);

    // informasi
    Route::resource('cariercenter', CarierCenterController::class);
    Route::resource('beasiswa', BeasiswaController::class);

    // setting
    Route::group(['prefix' => 'namalogo'], function ($router) {
        Route::get('/', [NamaLogoController::class, 'show']);
        Route::post('/update/{id}', [NamaLogoController::class, 'update']);
    });
    Route::group(['prefix' => 'footer'], function ($router) {
        Route::get('/', [FooterController::class, 'show']);
        Route::post('/update/{id}', [FooterController::class, 'update']);
    });

    Route::resource('fakultas', FakultasController::class);
    Route::resource('jurusan', JurusanController::class);
    Route::resource('streaming', StreamingController::class);
});
