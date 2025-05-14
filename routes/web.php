<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\ProgramStudiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\IndustriController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\KategoriIndustriController;
use App\Http\Controllers\LowonganController;

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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::pattern('id', '[0-9]+');


Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'postlogin']);
Route::get('signup', [AuthController::class, 'signup']);
Route::post('signup', [AuthController::class, 'postsignup'])->name('post.signup');
Route::get('logout', [AuthController::class, 'logout'])->middleware('auth:web,mahasiswa,dosen');

Route::get('/', [WelcomeController::class, 'index']);

Route::middleware(['auth:web,mahasiswa,dosen', \App\Http\Middleware\PreventBackHistory::class])->group(function() {

    Route::get('/', [WelcomeController::class, 'dashboard'])->name('home');

    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('profile.index');
        Route::get('/', [ProfileController::class, 'index'])->name('profile.index');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/update', [ProfileController::class, 'update'])->name('profile.update');
    });

    Route::prefix('dosen')->group(function () {
        Route::get('/', [DosenController::class, 'index'])->name('dosen.index');
        Route::post('/list', [DosenController::class, 'list']);
        Route::get('/create', [DosenController::class, 'create'])->name('dosen.create');
        Route::get('/{id}/show', [DosenController::class, 'show'])->name('dosen.show');
        Route::get('/{id}/edit', [DosenController::class, 'edit'])->name('dosen.edit');
        Route::delete('/{id}/delete', [DosenController::class, 'destroy'])->name('dosen.destroy');
        Route::post('/store', [DosenController::class, 'store'])->name('dosen.store');
        Route::post('/{id}/update', [DosenController::class, 'update'])->name('dosen.update');
        Route::get('{id}/delete', [DosenController::class, 'deleteModal'])->name('dosen.deleteModal');
        Route::delete('{id}/delete', [DosenController::class, 'delete_ajax'])->name('dosen.delete_ajax');
    });

    Route::prefix('mahasiswa')->group(function () {
        Route::get('/', [MahasiswaController::class, 'index'])->name('mahasiswa.index');
        Route::post('/list', [MahasiswaController::class, 'list']);
        Route::get('/create', [MahasiswaController::class, 'create'])->name('mahasiswa.create');
        Route::post('/', [MahasiswaController::class, 'store'])->name('mahasiswa.store');
        Route::get('/{id}/show', [MahasiswaController::class, 'show'])->name('mahasiswa.show');
        Route::get('/{id}/edit', [MahasiswaController::class, 'edit'])->name('mahasiswa.edit');
        Route::put('/{id}', [MahasiswaController::class, 'update'])->name('mahasiswa.update');
        Route::delete('/{id}/delete', [MahasiswaController::class, 'destroy'])->name('mahasiswa.destroy');
    });

    Route::prefix('admin')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('admin.index');
        Route::post('/list', [AdminController::class, 'list']);
        Route::get('/create', [AdminController::class, 'create'])->name('admin.create');
        Route::post('/', [AdminController::class, 'store'])->name('admin.store');
        Route::get('/{id}/show', [AdminController::class, 'show'])->name('admin.show');
        Route::get('/{id}/edit', [AdminController::class, 'edit'])->name('admin.edit');
        Route::put('/{id}', [AdminController::class, 'update'])->name('admin.update');
        Route::delete('/{id}/delete', [AdminController::class, 'destroy'])->name('admin.destroy');
    });

    Route::prefix('program-studi')->group(function () {
        Route::get('/', [ProgramStudiController::class, 'index'])->name('program-studi.index');
        Route::get('/list', [ProgramStudiController::class, 'list'])->name('program-studi.list');
        Route::get('/create', [ProgramStudiController::class, 'create'])->name('program-studi.create');
        Route::post('/', [ProgramStudiController::class, 'store'])->name('program-studi.store');
        Route::get('/{id}/show', [ProgramStudiController::class, 'show'])->name('program-studi.show');
        Route::get('/{id}/edit', [ProgramStudiController::class, 'edit'])->name('program-studi.edit');
        Route::put('/{id}', [ProgramStudiController::class, 'update'])->name('program-studi.update');
        Route::delete('/{id}/delete', [ProgramStudiController::class, 'destroy'])->name('program-studi.destroy');
    });
    
    Route::prefix('industri')->group(function () {
        Route::get('/', [IndustriController::class, 'index'])->name('industri.index');
        Route::post('/list', [IndustriController::class, 'list'])->name('industri.list');
        Route::get('/create', [IndustriController::class, 'create'])->name('industri.create');
        Route::post('/store', [IndustriController::class, 'store'])->name('industri.store');
        Route::get('/{id}/show', [IndustriController::class, 'show'])->name('industri.show');
        Route::get('/{id}/edit', [IndustriController::class, 'edit'])->name('industri.edit');
        Route::put('/{id}/update', [IndustriController::class, 'update'])->name('industri.update');
        Route::delete('/{id}/delete', [IndustriController::class, 'destroy'])->name('industri.destroy');
    });

    Route::prefix('kategori-industri')->group(function () {
        Route::get('/', [KategoriIndustriController::class, 'index'])->name('kategori-industri.index');
        Route::post('/list', [KategoriIndustriController::class, 'list'])->name('kategori-industri.list');
        Route::get('/create', [KategoriIndustriController::class, 'create'])->name('kategori-industri.create');
        Route::post('/store', [KategoriIndustriController::class, 'store'])->name('kategori-industri.store');
        Route::get('/{id}/show', [KategoriIndustriController::class, 'show'])->name('kategori-industri.show');
        Route::get('/{id}/edit', [KategoriIndustriController::class, 'edit'])->name('kategori-industri.edit');
        Route::put('/{id}/update', [KategoriIndustriController::class, 'update'])->name('kategori-industri.update');
        Route::delete('/{id}/delete', [KategoriIndustriController::class, 'destroy'])->name('kategori-industri.destroy');
    });

    Route::prefix('lowongan')->group(function () {
        Route::get('/', [LowonganController::class, 'index'])->name('lowongan.index');
        Route::post('/list', [LowonganController::class, 'list']);
        Route::post('/list', [LowonganController::class, 'list'])->name('lowongan.list');
        Route::get('/create', [LowonganController::class, 'create'])->name('lowongan.create');
        Route::post('/store', [LowonganController::class, 'store'])->name('lowongan.store');
        Route::get('/{id}/show', [LowonganController::class, 'show'])->name('lowongan.show');
        Route::get('/{id}/edit', [LowonganController::class, 'edit'])->name('lowongan.edit');
        Route::put('/{id}/update', [LowonganController::class, 'update'])->name('lowongan.update');
        Route::delete('/{id}/delete', [LowonganController::class, 'destroy'])->name('lowongan.destroy');
    });
});
?>





