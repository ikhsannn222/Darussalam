<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ProgramController;
use App\Http\Controllers\HomeController;

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
Route::prefix('program')->name('program.')->group(function () {

    // halaman index
    Route::get('/', [ProgramController::class, 'index'])->name('index');

    // simpan data baru (create)
    Route::post('/store', [ProgramController::class, 'store'])->name('store');

    // ambil 1 data untuk show / edit (dipakai AJAX modal)
    Route::get('/{id}', [ProgramController::class, 'show'])->name('show');

    // update data
    Route::put('/{id}', [ProgramController::class, 'update'])->name('update');

    // hapus data
    Route::delete('/{id}', [ProgramController::class, 'destroy'])->name('delete');
});
