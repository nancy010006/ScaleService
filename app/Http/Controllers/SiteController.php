<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Scale;
use Illuminate\Support\Facades\Auth;

class SiteController extends Controller
{
	public function __construct()
    {
        // $this->middleware('isUser')->except('logout');
    }
    public function index(){
        return view('site.index');
    }
    public function scales(){
        return view('site.scales');
    }
    public function register(){
        return view('site.register');
    }
    public function scale(Scale $scale){
        return view('site.scale',['id'=>$scale->id]);
    }
    public function records(){
        return view('site.records');
    }
    public function record(Scale $scale){
        return view('site.record',['id'=>$scale->id,'name'=>$scale->name]);
    }
    public function getAPIToken(){
        return Auth::user()->api_token;
    }
}
