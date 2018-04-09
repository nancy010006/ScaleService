<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function index(){
        return view('site.index');
    }
    public function default(){
        return view('site.default');
    }
    public function showLoginForm()
    {
        return view('site.login');
    }
}
