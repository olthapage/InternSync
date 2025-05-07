<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        $activeMenu = 'home';
       return view('welcome', compact('activeMenu')); // dashboard page
    }
}
