<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function admin()
    {
        return view('admin');
    }

    public function analista()
    {
        return view('analista');
    }

    public function supervisor()
    {
        return view('supervisor');
    }

    public function director()
    {
        return view('director');
    }
}
