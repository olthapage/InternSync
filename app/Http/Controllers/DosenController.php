<?php

namespace App\Http\Controllers;
use App\Models\DosenModel;

use Illuminate\Http\Request;

class DosenController extends Controller
{
    public function index()
    {
        $dosen = DosenModel::with('prodi')->get();
        return view('dosen.index', compact('dosen'));
    }
}
