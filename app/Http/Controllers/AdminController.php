<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Scale;

class AdminController extends Controller
{
    public function index(){
        return view('admin.index');
    }
    public function tables(){
        return view('admin.tables');
    }
    public function default(){
        return view('admin.default');
    }
    public function scale(){
        return view('admin.scale');
    }
    public function scaleadd(){
        return view('admin.scaleAdd');
    }
    public function scaleEdit(Scale $scale){
        return view('admin.scaleEdit',["scale"=>$scale]);
    }
}