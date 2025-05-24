<?php

namespace App\Http\Controllers\mahasiswa;

use App\Models\MagangModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class MagangController extends Controller
{
    public function index(){
        $mahasiswaId = Auth::id();
        $activeMenu = 'magang';
        $magang = MagangModel::where('mahasiswa_id', $mahasiswaId)->get();

        return view('mahasiswa_page.magang.index', compact('activeMenu', 'magang'));
    }
}
