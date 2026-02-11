<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FasilitasController;
use App\Http\Controllers\Admin\GuruController;
use App\Http\Controllers\Admin\ProgramController;
use App\Http\Controllers\Admin\TestimoniController;
use App\Http\Controllers\Admin\KegiatanController;
use App\Http\Controllers\Admin\MapController;
use App\Http\Controllers\Admin\PendaftaranController;
use App\Http\Controllers\Admin\PrestasiController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

/* ================= PUBLIC (WEB COMPRO) ================= */

// Halaman awal tidak perlu login
Route::get('/', function () {
    return view('welcome');
})->name('welcome');


/* ================= AUTH ================= */

Auth::routes();


/* ================= ADMIN AREA (WAJIB LOGIN) ================= */

Route::middleware(['auth'])->group(function () {

    /* ===== Dashboard ===== */
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard.index');


    /*
    |--------------------------------------------------------------------------
    | Program / Kelas
    |--------------------------------------------------------------------------
    */
    Route::get('/program', [ProgramController::class, 'index'])->name('program.index');
    Route::post('/program', [ProgramController::class, 'store'])->name('program.store');
    Route::get('/program/{id}', [ProgramController::class, 'show'])->name('program.show');
    Route::put('/program/{id}', [ProgramController::class, 'update'])->name('program.update');
    Route::delete('/program/{id}', [ProgramController::class, 'destroy'])->name('program.destroy');


    /*
    |--------------------------------------------------------------------------
    | Fasilitas
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
    | Guru
    |--------------------------------------------------------------------------
    */
    Route::prefix('guru')->name('guru.')->group(function () {
        Route::get('/', [GuruController::class, 'index'])->name('index');
        Route::post('/store', [GuruController::class, 'store'])->name('store');
        Route::get('/{id}', [GuruController::class, 'show'])->name('show');
        Route::put('/{id}', [GuruController::class, 'update'])->name('update');
        Route::delete('/{id}', [GuruController::class, 'destroy'])->name('delete');
    });


    /*
    |--------------------------------------------------------------------------
    | Pendaftaran
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')->group(function () {
        Route::resource('pendaftaran', PendaftaranController::class);
    });


    /*
    |--------------------------------------------------------------------------
    | Testimoni
    |--------------------------------------------------------------------------
    */
    Route::get('/testimoni', [TestimoniController::class, 'index'])->name('testimoni.index');
    Route::post('/testimoni', [TestimoniController::class, 'store'])->name('testimoni.store');
    Route::patch('/testimoni/{testimoni}/approve', [TestimoniController::class, 'approve'])->name('testimoni.approve');
    Route::delete('/testimoni/{testimoni}', [TestimoniController::class, 'destroy'])->name('testimoni.destroy');


    /*
    |--------------------------------------------------------------------------
    | Kegiatan
    |--------------------------------------------------------------------------
    */
    Route::prefix('kegiatan')->name('kegiatan.')->group(function () {
        Route::get('/', [KegiatanController::class, 'index'])->name('index');
        Route::post('/store', [KegiatanController::class, 'store'])->name('store');
        Route::get('/{id}', [KegiatanController::class, 'show'])->name('show');
        Route::put('/{id}', [KegiatanController::class, 'update'])->name('update');
        Route::delete('/{id}', [KegiatanController::class, 'destroy'])->name('delete');
    });


    /*
    |--------------------------------------------------------------------------
    | Maps
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')->group(function () {
        Route::get('/maps', [MapController::class, 'index'])->name('maps.index');
    });

});

Route::prefix('prestasi')->name('prestasi.')->group(function () {
    Route::get('/', [PrestasiController::class, 'index'])->name('index');
    Route::post('/store', [PrestasiController::class, 'store'])->name('store');
    Route::get('/{id}', [PrestasiController::class, 'show'])->name('show');
    Route::put('/{id}', [PrestasiController::class, 'update'])->name('update');
    Route::delete('/{id}', [PrestasiController::class, 'destroy'])->name('delete');
});
