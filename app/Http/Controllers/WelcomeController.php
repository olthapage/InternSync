<?php

namespace App\Http\Controllers;

use App\Models\MagangModel;
use Illuminate\Http\Request;
use App\Models\IndustriModel;
use App\Models\MahasiswaModel;
use App\Models\DetailLowonganModel;
use Illuminate\Support\Facades\Auth;

class WelcomeController extends Controller
{
    public function index()
    {
         $evaluasi = MagangModel::all();
        if (auth()->check()) {
            return redirect('/dashboard');
        }
        return view('landing', compact('evaluasi'));
    }

    public function landing()
    {
        $evaluasi = MagangModel::whereIn('mahasiswa_magang_id', [1, 2, 3])->get();
        if (auth()->check()) {
            return redirect()->route('home');
        }

        return view('landing', compact('evaluasi'));
    }

    public function dashboard() {
        $mhsCount = MahasiswaModel::count();
        $mhsMagang = MahasiswaModel::where('status', true)->count();
        $industri = IndustriModel::count();
        $lowongan = DetailLowonganModel::count();

        $activeMenu = 'home';
        if (Auth::guard('web')->check()) {
            return view('admin_page.dashboard', compact('activeMenu', 'mhsCount', 'mhsMagang', 'industri', 'lowongan'));
        }
        if (Auth::guard('dosen')->check()) {
            return view('dosen_page.dashboard', compact('activeMenu', 'mhsCount', 'mhsMagang', 'industri', 'lowongan'));
        }
        if (Auth::guard('mahasiswa')->check()) {
            return view('mahasiswa_page.dashboard', compact('activeMenu', 'mhsCount', 'mhsMagang', 'industri', 'lowongan'));
        }
        if (Auth::guard('industri')->check()) {
            return view('industri_page.dashboard', compact('activeMenu', 'mhsCount', 'mhsMagang', 'industri', 'lowongan'));
        }

    }
}
