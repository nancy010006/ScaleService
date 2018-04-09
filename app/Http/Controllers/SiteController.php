<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Scale;

class SiteController extends Controller
{
	public function __construct()
    {
        $this->middleware('isUser')->except('logout');
    }
    public function index(){
        return view('site.index');
    }
    public function scales(){
        return view('site.scales');
    }
    public function scale(Scale $scale){
        return view('site.scale',['id'=>$scale->id]);
    }
}
