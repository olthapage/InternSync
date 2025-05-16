<?php

namespace App\Http\Controllers;

use App\Models\MagangModel;
use Illuminate\Http\Request;
use App\Models\IndustriModel;
use App\Models\MahasiswaModel;
use App\Models\DetailLowonganModel;

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
        return view('welcome', compact('activeMenu', 'mhsCount', 'mhsMagang', 'industri', 'lowongan'));
    }
}
