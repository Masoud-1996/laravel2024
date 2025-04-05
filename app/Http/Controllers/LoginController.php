<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    //@desc show login form
    //@route GET /login
    public function login(): View
    {
        return view('auth.login');
    }

    //@desc Authenticate User
    //@route POST /login
    public function authenticate(Request $request): RedirectResponse
    {

        $credentials = $request->validate([

            'email' => 'required|email|string|max:100',
            'password' => 'required|string',

        ]);

        // dd($credentials);

        //Attempt to authenticate user
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('home'))->with('success', 'You are Now Logged in!');
        }
        //If Auth fails, redirect nack with error
        return back()->withErrors([
            'email' => 'The Provided credentials do not match our records',

        ])->onlyInput('email');
    }

    //@desc Logout user form
    //@route POST /Logout
    public function Logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
