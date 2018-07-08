<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class isUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect('/site/login')->with('url',url()->current());
        }
        if(Auth::User()->auth!=0){
            return redirect('/site/login')->with('url',url()->current());
        }
        return $next($request);
    }
}
