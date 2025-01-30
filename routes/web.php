<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProdukController;

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
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//admin routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/transaksi', [AdminController::class, 'Transaksi'])->name('admin.transaksi');
    Route::delete('/admin/transaksi/hapus/{id}', [AdminController::class, 'destroy'])->name('transaksi.hapus');
    Route::resource('/admin/produks', ProdukController::class);
});

//agent routes
Route::middleware(['auth', 'role:agent'])->group(function () {
    Route::get('/agent/dashboard', [AgentController::class, 'dashboard'])->name('agent.dashboard');
    Route::get('/agent/kasir', [AgentController::class, 'kasir'])->name('agent.kasir');
    Route::get('/getproduk', [AgentController::class, 'getProduk']);
    Route::post('/simpantransaksi', [AgentController::class, 'simpantransaksi'])->name('simpantransaksi');
    Route::get('/agent/lihat-transaksi/{id}', [AgentController::class, 'lihatTransaksi'])->name('agent.lihatTransaksi');
    Route::get('/transaksi', [AgentController::class, 'semuaTransaksi'])->name('transaksi.semua');
    Route::get('/transaksi/cetak-pdf/{id}', [AgentController::class, 'cetakPDF']);
});

require __DIR__ . '/auth.php';
