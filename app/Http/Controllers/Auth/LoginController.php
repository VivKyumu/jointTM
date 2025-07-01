<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;    


class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest')->only(['create', 'store']); // login form & login action
        $this->middleware('auth')->only('destroy'); // logout
    }

    /**
     * Determine where to redirect users after login.
     */
    protected function redirectTo()
{
    if (Auth::check() && Auth::user()->is_admin) {
        return route('admin.dashboard');
    }

    return route('dashboard');
}


}
