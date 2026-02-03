<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ProgramController;
use App\Http\Controllers\Admin\FasilitasController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\GuruController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

/* Auth bawaan Laravel */
Auth::routes();

/* Home setelah login */
Route::get('/home', [HomeController::class, 'index'])->name('home');

/*
|--------------------------------------------------------------------------
| Program / Kelas CRUD
|--------------------------------------------------------------------------
*/
Route::get('/program', [ProgramController::class, 'index'])->name('program.index');
Route::post('/program', [ProgramController::class, 'store'])->name('program.store');
Route::get('/program/{id}', [ProgramController::class, 'show'])->name('program.show'); // JSON
Route::put('/program/{id}', [ProgramController::class, 'update'])->name('program.update');
Route::delete('/program/{id}', [ProgramController::class, 'destroy'])->name('program.destroy'); // JSON

/*
|--------------------------------------------------------------------------
| Fasilitas CRUD
|--------------------------------------------------------------------------
*/
Route::prefix('fasilitas')->name('fasilitas.')->group(function () {

    Route::get('/', [FasilitasController::class, 'index'])->name('index');
    Route::post('/store', [FasilitasController::class, 'store'])->name('store');
    Route::get('/{id}', [FasilitasController::class, 'show'])->name('show');
    Route::put('/{id}', [FasilitasController::class, 'update'])->name('update');
    Route::delete('/{id}', [FasilitasController::class, 'destroy'])->name('delete');
});


/*
|--------------------------------------------------------------------------
| Guru CRUD
|--------------------------------------------------------------------------
*/
Route::prefix('guru')->name('guru.')->group(function () {

    Route::get('/', [GuruController::class, 'index'])->name('index');
    Route::post('/store', [GuruController::class, 'store'])->name('store');
    Route::get('/{id}', [GuruController::class, 'show'])->name('show');
    Route::put('/{id}', [GuruController::class, 'update'])->name('update');
    Route::delete('/{id}', [GuruController::class, 'destroy'])->name('delete');
});

