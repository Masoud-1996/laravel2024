<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{

    //@desc show home index view
    //@route GET /
    public function index(): View
    {
        /*for session test
         session()->put('test', '123');
         session()->forget('test');
        */

        $jobs = Job::latest()->limit(6)->get();
        // return view('pages.index ', compact('jobs'));
        return view('pages.index ')->with('jobs', $jobs);
    }
}
