<?php

use App\Http\Controllers\AnalyticController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\InstrumentController;
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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::post('/user/{id}', [UserController::class, 'update'])->name('updateUser');
    Route::post('/delete/user/{id}', [UserController::class, 'destroy'])->name('deleteUser');
    Route::post('/users', [RegisteredUserController::class, 'store'])->name('registerUser');

    Route::get('/parameters-testing', [ParameterTestingController::class, 'index'])->name('parametersTesting');
    Route::post('/parameters-testing', [ParameterTestingController::class, 'store'])->name('storeParametersTesting');
    Route::post('/update/parameters-testing/{id}', [ParameterTestingController::class, 'update'])->name('updateParametersTesting');
    Route::post('/delete/parameters-testing/{id}', [ParameterTestingController::class, 'delete'])->name('deleteParametersTesting');

    Route::get('/samples', [SampleController::class, 'index'])->name('samples');
    Route::post('/sample', [SampleController::class, 'store'])->name('storeSamples');
    Route::post('/update/sample/{id}', [SampleController::class, 'update'])->name('updateSample');
    Route::post('/delete/sample/{id}', [SampleController::class, 'delete'])->name('deleteSample');

    Route::get('/instruments', [InstrumentController::class, 'index'])->name('instruments');
    Route::post('/instruments', [InstrumentController::class, 'store'])->name('storeInstruments');
    Route::post('/update/instrument/{id}', [InstrumentController::class, 'update'])->name('updateInstrument');
    Route::post('/delete/instrument/{id}', [InstrumentController::class, 'delete'])->name('deleteInstrument');

    Route::get('/analitycs', [AnalyticController::class, 'index'])->name('analitics');
    Route::post('/store/analitycs', [AnalyticController::class, 'store'])->name('storeAnalitics');
    Route::get('/show/sample/analitycs/{qrcode}', [AnalyticController::class, 'showSample'])->name('showSample');
    Route::get('/show/instrument/analitycs/{qrcode}', [AnalyticController::class, 'showInstrument'])->name('showInstrument');
});

require __DIR__.'/auth.php';
