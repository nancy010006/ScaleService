<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    // use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected $redirectTo = '/admin';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('isUser')->except('logout');
    }

    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'auth' =>2])) {
            return redirect('/admin');
        }else{
            return view('/admin/login')->withErrors(array(
            'msg' => '帳號或密碼錯誤'
            ));
        }
    }
    public function logout(Request $request)
    {
    	Auth::logout();
        return redirect('/admin');
    }
    public function showLoginForm()
    {
        return view('admin.login');
    }
}
