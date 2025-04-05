<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisterController extends Controller
{
    //@desc show register form
    //@route GET /register
    public function register(): View
    {
        return view('auth.register');
    }

    //@desc Store user in database
    //@route POST /register
    public function store(Request $request): RedirectResponse
    {

        $validatedData = $request->validate([

            'name' => 'required|string|max:100',
            'email' => 'required|email|string|max:100|unique:users',
            'password' => 'required|string|min:8|confirmed',

        ]);

        // Hash password
        $validatedData['password'] = Hash::make($validatedData['password']);

        // Create user
        $user = User::create($validatedData);

        return redirect()->route('login')->with('success', 'You Are Registered and can log in!');
    }
}
