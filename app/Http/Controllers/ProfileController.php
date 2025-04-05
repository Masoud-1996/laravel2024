<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    //@ desc Update Profile Info
    //@ route PUT /profile

    public function update(Request $request): RedirectResponse
    {
        //Get Logged in user
        $user = Auth::user();

        //validate data
        $validatedData = $request->validate([

            'name' => 'required|string',
            'email' => 'required|string|email',
            'avatar' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048'
        ]);

        //Get user name and email
        $user->name = $request->input('name');
        $user->email = $request->input('email');

        //Handle avatar upload
        if ($request->hasFile('avatar')) {
            //Delete old avatars if exists
            if ($user->avatar) {
                Storage::delete('public/' . $user->avatar);
            }

            //Store new avatar
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $avatarPath;
        }


        //update user info
        // $user->update($validatedData);
        $user->save();

        return redirect()->route('dashboard')->with('success', 'Profile info updated!');
    }
}
