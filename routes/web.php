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

Route::middleware(['auth:web,mahasiswa,dosen'])->group(function() {

    Route::get('/dashboard', [WelcomeController::class, 'dashboard'])->name('home');

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/dosen', [DosenController::class, 'index'])->name('dosen.index');
    Route::post('/dosen/list', [DosenController::class, 'list']);
    Route::get('/dosen/create', [DosenController::class, 'create'])->name('dosen.create');
    Route::post('/dosen', [DosenController::class, 'store'])->name('dosen.store');
    Route::get('/dosen/{id}/show', [DosenController::class, 'show'])->name('dosen.show');
    Route::get('/dosen/{id}/edit', [DosenController::class, 'edit'])->name('dosen.edit');
    Route::put('/dosen/{id}', [DosenController::class, 'update'])->name('dosen.update');
    Route::delete('/dosen/{id}/delete', [DosenController::class, 'destroy'])->name('dosen.destroy');

    Route::get('/mahasiswa', [MahasiswaController::class, 'index'])->name('mahasiswa.index');
    Route::post('/mahasiswa/list', [MahasiswaController::class, 'list']);
    Route::get('/mahasiswa/create', [MahasiswaController::class, 'create'])->name('mahasiswa.create');
    Route::post('/mahasiswa', [MahasiswaController::class, 'store'])->name('mahasiswa.store');
    Route::get('/mahasiswa/{id}/show', [MahasiswaController::class, 'show'])->name('mahasiswa.show');
    Route::get('/mahasiswa/{id}/edit', [MahasiswaController::class, 'edit'])->name('mahasiswa.edit');
    Route::put('/mahasiswa/{id}', [MahasiswaController::class, 'update'])->name('mahasiswa.update');
    Route::delete('/mahasiswa/{id}/delete', [MahasiswaController::class, 'destroy'])->name('mahasiswa.destroy');

    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::post('/admin/list', [AdminController::class, 'list']);
    Route::get('/admin/create', [AdminController::class, 'create'])->name('admin.create');
    Route::post('/admin', [AdminController::class, 'store'])->name('admin.store');
    Route::get('/admin/{id}/show', [AdminController::class, 'show'])->name('admin.show');
    Route::get('/admin/{id}/edit', [AdminController::class, 'edit'])->name('admin.edit');
    Route::put('/admin/{id}', [AdminController::class, 'update'])->name('admin.update');
    Route::delete('/admin/{id}/delete', [AdminController::class, 'destroy'])->name('admin.destroy');

    Route::get('/program-studi', [ProgramStudiController::class, 'index'])->name('program-studi.index');
    Route::get('/program-studi/list', [ProgramStudiController::class, 'list'])->name('program-studi.list');
    Route::get('/program-studi/create', [ProgramStudiController::class, 'create'])->name('program-studi.create');
    Route::post('/program-studi', [ProgramStudiController::class, 'store'])->name('program-studi.store');
    Route::get('/program-studi/{id}/show', [ProgramStudiController::class, 'show'])->name('program-studi.show');
    Route::get('/program-studi/{id}/edit', [ProgramStudiController::class, 'edit'])->name('program-studi.edit');
    Route::put('/program-studi/{id}', [ProgramStudiController::class, 'update'])->name('program-studi.update');
    Route::delete('/program-studi/{id}/delete', [ProgramStudiController::class, 'destroy'])->name('program-studi.destroy');


    Route::get('/industri/', [IndustriController::class, 'index'])->name('industri.index');
    Route::post('/industri/list', [IndustriController::class, 'list'])->name('industri.list');
    Route::get('/industri/create', [IndustriController::class, 'create'])->name('industri.create');
    Route::post('/industri/store', [IndustriController::class, 'store'])->name('industri.store');
    Route::get('/industri/{id}/show', [IndustriController::class, 'show'])->name('industri.show');
    Route::get('/industri/{id}/edit', [IndustriController::class, 'edit'])->name('industri.edit');
    Route::put('/industri/{id}/update', [IndustriController::class, 'update'])->name('industri.update');
    Route::delete('/industri/{id}/delete', [IndustriController::class, 'destroy'])->name('industri.destroy');

    Route::get('/kategori-industri/', [KategoriIndustriController::class, 'index'])->name('kategori-industri.index');
    Route::post('/kategori-industri/list', [KategoriIndustriController::class, 'list'])->name('kategori-industri.list');
    Route::get('/kategori-industri/create', [KategoriIndustriController::class, 'create'])->name('kategori-industri.create');
    Route::post('/kategori-industri/store', [KategoriIndustriController::class, 'store'])->name('kategori-industri.store');
    Route::get('/kategori-industri/{id}/show', [KategoriIndustriController::class, 'show'])->name('kategori-industri.show');
    Route::get('/kategori-industri/{id}/edit', [KategoriIndustriController::class, 'edit'])->name('kategori-industri.edit');
    Route::put('/kategori-industri/{id}/update', [KategoriIndustriController::class, 'update'])->name('kategori-industri.update');
    Route::delete('/kategori-industri/{id}/delete', [KategoriIndustriController::class, 'destroy'])->name('kategori-industri.destroy');

    Route::get('/lowongan', [LowonganController::class, 'index'])->name('lowongan.index');
    Route::post('/lowongan/list', [LowonganController::class, 'list']);
    Route::post('/lowongan/list', [LowonganController::class, 'list'])->name('lowongan.list');
    Route::get('/lowongan/create', [LowonganController::class, 'create'])->name('lowongan.create');
    Route::post('/lowongan/store', [LowonganController::class, 'store'])->name('lowongan.store');
    Route::get('/lowongan/{id}/show', [LowonganController::class, 'show'])->name('lowongan.show');
    Route::get('/lowongan/{id}/edit', [LowonganController::class, 'edit'])->name('lowongan.edit');
    Route::put('/lowongan/{id}/update', [LowonganController::class, 'update'])->name('lowongan.update');
    Route::delete('/lowongan/{id}/delete', [LowonganController::class, 'destroy'])->name('lowongan.destroy');
});

?>





