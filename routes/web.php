<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\DetailPembelianController;
use App\Http\Controllers\DetailPenjualanController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\ObatController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group.
|
*/

// Route publik / home
Route::get('/', function(){ return redirect()->route('dashboard'); })->name('home');

// dashboard route (kedua role boleh)
Route::get('/dashboard', function(){
    return view('dashboard');
})->middleware('auth')->name('dashboard');


/*
|--------------------------------------------------------------------------
| MASTER DATA (CRUD)
|--------------------------------------------------------------------------
*/

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// Pegawai hanya boleh akses dashboard & penjualan routes
Route::middleware(['auth','role:pegawai,owner'])->group(function(){
    Route::resource('penjualan', \App\Http\Controllers\PenjualanController::class);
    Route::get('penjualan/{penjualan}/detail', [\App\Http\Controllers\DetailPenjualanController::class,'index'])->name('penjualan.detail.index');
    Route::post('penjualan/{penjualan}/detail', [\App\Http\Controllers\DetailPenjualanController::class,'store'])->name('penjualan.detail.store');
    Route::put('penjualan/detail/{detail}', [\App\Http\Controllers\DetailPenjualanController::class,'update'])->name('penjualan.detail.update');
    Route::delete('penjualan/detail/{detail}', [\App\Http\Controllers\DetailPenjualanController::class,'destroy'])->name('penjualan.detail.destroy');
});

// Owner can access everything (master + pembelian + other)
Route::middleware(['auth','role:owner'])->group(function(){
    Route::resource('obat', \App\Http\Controllers\ObatController::class);
    Route::resource('supplier', \App\Http\Controllers\SupplierController::class);
    Route::resource('pelanggan', \App\Http\Controllers\PelangganController::class);

    Route::resource('pembelian', \App\Http\Controllers\PembelianController::class);
    Route::get('pembelian/{pembelian}/detail', [\App\Http\Controllers\DetailPembelianController::class,'index'])->name('pembelian.detail.index');
    Route::post('pembelian/{pembelian}/detail', [\App\Http\Controllers\DetailPembelianController::class,'store'])->name('pembelian.detail.store');
    Route::put('pembelian/detail/{detail}', [\App\Http\Controllers\DetailPembelianController::class,'update'])->name('pembelian.detail.update');
    Route::delete('pembelian/detail/{detail}', [\App\Http\Controllers\DetailPembelianController::class,'destroy'])->name('pembelian.detail.destroy');
});