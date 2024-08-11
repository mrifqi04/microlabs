<?php

use App\Http\Controllers\AnalyticController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ForgetPasswordController;
use App\Http\Controllers\InstrumentController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\MediaAnalyticController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\ParameterTestingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SampleController;
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

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/forget-password', [ForgetPasswordController::class, 'index'])->name('forgetPassword');
Route::post('/validate/forget-password', [ForgetPasswordController::class, 'validateData'])->name('validateForgotPassword');
Route::post('/save/new-password', [ForgetPasswordController::class, 'postNewPassword'])->name('postNewPassword');


Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/analytic/area-chart', [DashboardController::class, 'areaAnalytic']);
    Route::get('/parameter-testing/bar-chart', [DashboardController::class, 'parameterTestingChart']);
    Route::post('/export/analytics', [DashboardController::class, 'exportAnalytic'])->name('exportAnalytic');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::group(['middleware' => ['SuperAdmin', 'Administrator']], function () {
        Route::get('/users', [UserController::class, 'index'])->name('users');
        Route::post('/user/{id}', [UserController::class, 'update'])->name('updateUser');
        Route::post('/delete/user/{id}', [UserController::class, 'destroy'])->name('deleteUser');
        Route::post('/users', [RegisteredUserController::class, 'store'])->name('registerUser');
    });

    Route::get('/parameters-testing', [ParameterTestingController::class, 'index'])->name('parametersTesting');
    Route::post('/parameters-testing', [ParameterTestingController::class, 'store'])->name('storeParametersTesting');
    Route::post('/update/parameters-testing/{id}', [ParameterTestingController::class, 'update'])->name('updateParametersTesting');
    Route::post('/delete/parameters-testing/{id}', [ParameterTestingController::class, 'delete'])->name('deleteParametersTesting');

    Route::get('/samples', [SampleController::class, 'index'])->name('samples');
    Route::get('/json/samples', [SampleController::class, 'jsonSamples']);
    Route::get('/count-sample/{typeID}',  [SampleController::class, 'countSample']);
    Route::post('/sample', [SampleController::class, 'store'])->name('storeSamples');
    Route::post('/update/sample/{id}', [SampleController::class, 'update'])->name('updateSample');
    Route::post('/delete/sample/{id}', [SampleController::class, 'delete'])->name('deleteSample');
    Route::get('/generate/barcode/{id}', [SampleController::class, 'generateBarcode'])->name('generateBarcode');

    Route::get('/instruments', [InstrumentController::class, 'index'])->name('instruments');
    Route::post('/instruments', [InstrumentController::class, 'store'])->name('storeInstruments');
    Route::post('/update/instrument/{id}', [InstrumentController::class, 'update'])->name('updateInstrument');
    Route::post('/delete/instrument/{id}', [InstrumentController::class, 'delete'])->name('deleteInstrument');

    Route::get('/analitycs', [AnalyticController::class, 'index'])->name('analitics');
    Route::post('/store/analitycs', [AnalyticController::class, 'store'])->name('storeAnalitics');
    Route::get('/show/sample/analitycs/{qrcode}', [AnalyticController::class, 'showSample'])->name('showSample');
    Route::get('/show/instrument/analitycs/{qrcode}', [AnalyticController::class, 'showInstrument'])->name('showInstrument');
    Route::get('/edit/sample-microba/{sampleId}/{instrumentId}', [AnalyticController::class, 'addMicrobaToSample'])->name('addMicroba');
    Route::post('/edit/sample-microba/{sampleId}/{instrumentId}', [AnalyticController::class, 'submitMicrobaToSample'])->name('submitMicroba');
    Route::get('/analytic/replication', [AnalyticController::class, 'replication']);


    Route::get('/media-analitycs', [MediaAnalyticController::class, 'index'])->name('mediaAnalitics');
    Route::get('/show/media/analitycs/{qrcode}', [MediaAnalyticController::class, 'showMedia'])->name('showMedia');
    Route::post('/store/media/analitycs', [MediaAnalyticController::class, 'store'])->name('storeAnaliticMedia');


    Route::get('/medias', [MediaController::class, 'index'])->name('medias');
    Route::post('/medias', [MediaController::class, 'store'])->name('storeMedia');
    Route::post('/update/media/{id}', [MediaController::class, 'update'])->name('updateMedia');
    Route::post('/delete/media/{id}', [MediaController::class, 'delete'])->name('deleteMedia');
    Route::get('/generate/barcode/media/{id}', [MediaController::class, 'generateBarcode'])->name('generateBarcodeMedia');

    Route::get('/logs', [LogController::class, 'index'])->name('logs');
});

require __DIR__ . '/auth.php';
