<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $admin = UserModel::all();
        return view('admin.index', compact('admin'));
    }
}
