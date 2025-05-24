<?php

    use App\Http\Controllers\admin\AdminController;
    use App\Http\Controllers\admin\DaftarSkillController;
    use App\Http\Controllers\admin\DosenController;
    use App\Http\Controllers\admin\IndustriController;
    use App\Http\Controllers\admin\KategoriIndustriController;
    use App\Http\Controllers\admin\LowonganController;
    use App\Http\Controllers\admin\MagangController;
    use App\Http\Controllers\admin\MahasiswaController;
    use App\Http\Controllers\admin\PengajuanController;
    use App\Http\Controllers\admin\ProgramStudiController;
    use App\Http\Controllers\AuthController;
    use App\Http\Controllers\InternController;
    use App\Http\Controllers\mahasiswa\LowonganController as MahasiswaLowonganController;
    use App\Http\Controllers\mahasiswa\PengajuanController as MahasiswaPengajuanController;
    use App\Http\Controllers\mahasiswa\MagangController as MahasiswaMagangController;
    use App\Http\Controllers\mahasiswa\VerifikasiController;
    use App\Http\Controllers\ProfileController;
    use App\Http\Controllers\WelcomeController;
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

    // Route::get('/', function () {
    //     return view('welcome');
    // });

    Route::pattern('id', '[0-9]+');

    Route::get('/', [WelcomeController::class, 'landing'])->name('landing');

    Route::get('login', [AuthController::class, 'login'])->name('login');
    Route::post('login', [AuthController::class, 'postlogin']);
    Route::get('signup', [AuthController::class, 'signup'])->name('signup');
    Route::post('signup', [AuthController::class, 'postsignup'])->name('post.signup');
    Route::get('logout', [AuthController::class, 'logout'])->middleware('auth:web,mahasiswa,dosen');

    Route::middleware(['auth:web,mahasiswa,dosen', \App\Http\Middleware\PreventBackHistory::class])->group(function () {

        Route::get('/dashboard', [WelcomeController::class, 'dashboard'])->name('home');

        Route::prefix('intern')->as('intern.')->group(function () {
            Route::get('academic-form', [InternController::class, 'showAcademicProfile'])->name('academicProfile');
            Route::post('academic-form', [InternController::class, 'storeAcademicProfile'])->name('storeAcademicProfile');
            Route::get('preferences', [InternController::class, 'showPreferences'])->name('preferences');
            Route::post('preferences', [InternController::class, 'updatePreferences'])->name('updatePreferences');
        });

        /*      --Route admin disini--      */
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
            Route::get('/{id}/show', [MahasiswaController::class, 'show'])->name('mahasiswa.show');
            Route::get('{id}/verifikasi', [MahasiswaController::class, 'verifikasi'])->name('mahasiswa.verifikasi');
            Route::put('{id}/verifikasi', [MahasiswaController::class, 'updateVerifikasi'])->name('mahasiswa.updateVerifikasi');
            Route::get('/{id}/edit', [MahasiswaController::class, 'edit'])->name('mahasiswa.edit');
            Route::delete('/{id}/delete', [MahasiswaController::class, 'destroy'])->name('mahasiswa.destroy');
            Route::post('/store', [MahasiswaController::class, 'store'])->name('mahasiswa.store');
            Route::post('/{id}/update', [MahasiswaController::class, 'update'])->name('mahasiswa.update');
            Route::get('{id}/delete', [MahasiswaController::class, 'deleteModal'])->name('mahasiswa.deleteModal');
            Route::delete('{id}/delete', [MahasiswaController::class, 'delete_ajax'])->name('mahasiswa.delete_ajax');
        });

        Route::prefix('admin')->group(function () {
            Route::get('/', [AdminController::class, 'index'])->name('admin.index');
            Route::post('/list', [AdminController::class, 'list']);
            Route::get('/create', [AdminController::class, 'create'])->name('admin.create');
            Route::get('/{id}/show', [AdminController::class, 'show'])->name('admin.show');
            Route::get('/{id}/edit', [AdminController::class, 'edit'])->name('admin.edit');
            Route::delete('/{id}/delete', [AdminController::class, 'destroy'])->name('admin.destroy');
            Route::post('/store', [AdminController::class, 'store'])->name('admin.store');
            Route::post('/{id}/update', [AdminController::class, 'update'])->name('admin.update');
            Route::get('{id}/delete', [AdminController::class, 'deleteModal'])->name('admin.deleteModal');
            Route::delete('{id}/delete', [AdminController::class, 'delete_ajax'])->name('admin.delete_ajax');
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
            Route::delete('{id}/delete', [ProgramStudiController::class, 'delete_ajax'])->name('program-studi.delete');
        });

        Route::prefix('industri')->group(function () {
            Route::get('/', [IndustriController::class, 'index'])->name('industri.index');
            Route::post('/list', [IndustriController::class, 'list'])->name('industri.list');
            Route::get('/create', [IndustriController::class, 'create'])->name('industri.create');
            Route::post('/store', [IndustriController::class, 'store'])->name('industri.store');
            Route::get('/{id}/show', [IndustriController::class, 'show'])->name('industri.show');
            Route::get('/{id}/edit', [IndustriController::class, 'edit'])->name('industri.edit');
            Route::post('/{id}/update', [IndustriController::class, 'update'])->name('industri.update');
            Route::delete('/{id}/delete', [IndustriController::class, 'destroy'])->name('industri.destroy');
            Route::delete('{id}/delete', [LowonganController::class, 'delete_ajax'])->name('lowongan.delete');
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

        Route::prefix('skill')->group(function () {
            Route::get('/', [DaftarSkillController::class, 'index'])->name('detail-skill.index');
            Route::get('/list', [DaftarSkillController::class, 'list'])->name('detail-skill.list');
            Route::get('/create', [DaftarSkillController::class, 'create'])->name('detail-skill.create');
            Route::post('/store', [DaftarSkillController::class, 'store'])->name('detail-skill.store');
            Route::get('{id}/show', [DaftarSkillController::class, 'show'])->name('detail-skill.show');
            Route::get('{id}/edit', [DaftarSkillController::class, 'edit'])->name('detail-skill.edit');
            Route::post('{id}/update', [DaftarSkillController::class, 'update'])->name('detail-skill.update');
            Route::get('{id}/delete', [DaftarSkillController::class, 'deleteModal'])->name('detail-skill.deleteModal');
            Route::delete('{id}/delete', [DaftarSkillController::class, 'delete_ajax'])->name('detail-skill.delete_ajax');
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
            Route::delete('{id}/delete', [LowonganController::class, 'delete_ajax'])->name('lowongan.delete');
        });

        Route::prefix('pengajuan')->group(function () {
            Route::get('/', [PengajuanController::class, 'index'])->name('pengajuan.index');
            Route::post('/list', [PengajuanController::class, 'list']);
            Route::get('/create', [PengajuanController::class, 'create'])->name('pengajuan.create');
            // Route pengajuan dengan id lowongan
            Route::get('/{id}/create', [MahasiswaPengajuanController::class, 'create'])->name('mahasiswa.pengajuan.createWithLowongan');
            Route::get('/{id}/show', [PengajuanController::class, 'show'])->name('pengajuan.show');
            Route::get('/{id}/edit', [PengajuanController::class, 'edit'])->name('pengajuan.edit');
            Route::delete('/{id}/delete', [PengajuanController::class, 'destroy'])->name('pengajuan.destroy');
            Route::post('/store', [PengajuanController::class, 'store'])->name('pengajuan.store');
            Route::post('/{id}/update', [PengajuanController::class, 'update'])->name('pengajuan.update');
            Route::get('{id}/delete', [PengajuanController::class, 'deleteModal'])->name('pengajuan.deleteModal');
            Route::delete('{id}/delete', [PengajuanController::class, 'delete_ajax'])->name('pengajuan.delete_ajax');
        });

        Route::prefix('magang')->group(function () {
            Route::get('/', [MagangController::class, 'index'])->name('magang.index');
            Route::post('/list', [MagangController::class, 'list'])->name('magang.list');
        });

        // Halaman Mahasiswa
        Route::prefix('mahasiswa/lowongan')->group(function () {
            Route::get('/', [MahasiswaLowonganController::class, 'index'])->name('mahasiswa.lowongan.index');
            Route::post('/list', [MahasiswaLowonganController::class, 'list'])->name('mahasiswa.lowongan.list');
            Route::get('/create', [MahasiswaLowonganController::class, 'create'])->name('mahasiswa.lowongan.create');
            Route::post('/store', [MahasiswaLowonganController::class, 'store'])->name('mahasiswa.lowongan.store');
            Route::get('/{id}/show', [MahasiswaLowonganController::class, 'show'])->name('mahasiswa.lowongan.show');
            Route::get('/{id}/edit', [MahasiswaLowonganController::class, 'edit'])->name('mahasiswa.lowongan.edit');
            Route::put('/{id}/update', [MahasiswaLowonganController::class, 'update'])->name('mahasiswa.lowongan.update');
            Route::delete('/{id}/delete', [MahasiswaLowonganController::class, 'destroy'])->name('mahasiswa.lowongan.destroy');
        });

        Route::prefix('mahasiswa/pengajuan')->group(function () {
            Route::get('/', [MahasiswaPengajuanController::class, 'index'])->name('mahasiswa.pengajuan.index');
            Route::get('/create', [MahasiswaPengajuanController::class, 'create'])->name('mahasiswa.pengajuan.create');
            Route::post('/store', [MahasiswaPengajuanController::class, 'store'])->name('mahasiswa.pengajuan.store');
            Route::get('/{id}/show', [MahasiswaPengajuanController::class, 'show'])->name('mahasiswa.pengajuan.show');
            Route::get('/{id}/edit', [MahasiswaPengajuanController::class, 'edit'])->name('mahasiswa.pengajuan.edit');
            Route::put('/{id}/update', [MahasiswaPengajuanController::class, 'update'])->name('mahasiswa.pengajuan.update');
            Route::delete('/{id}/delete', [MahasiswaPengajuanController::class, 'destroy'])->name('mahasiswa.pengajuan.destroy');
        });

        Route::prefix('mahasiswa/verifikasi')->group(function () {
            Route::post('/store', [VerifikasiController::class, 'store'])->name('mahasiswa.verifikasi.store');
        });

        Route::prefix('mahasiswa/magang')->group(function() {
            Route::get('/', [MahasiswaMagangController::class, 'index'])->name('mahasiswa.magang.index');
        });
});
