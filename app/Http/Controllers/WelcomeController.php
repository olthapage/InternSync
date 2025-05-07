<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IndustriModel;
use App\Models\MahasiswaModel;
use App\Models\DetailLowonganModel;

class WelcomeController extends Controller
{
    public function index()
    {
        if (auth()->check()) {
            return redirect('/dashboard');
        }
        return view('auth.login'); // login page
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
