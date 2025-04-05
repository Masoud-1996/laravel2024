<?php

use App\Http\Controllers\ApplicantController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JobController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\GeocodeController;

// Route::get('/jobs', [JobController::class, 'index']);
// Route::get('/jobs/create', [JobController::class, 'create']);
// Route::get('/jobs/{id}', [JobController::class, 'show']);
// Route::post('/jobs', [JobController::class, 'store']);


Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('jobs/search', [JobController::class, 'search'])->name('jobs.search');
Route::resource('jobs', JobController::class)->middleware('auth')->only(['create', 'edit', ' update', 'destroy']);
Route::resource('jobs', JobController::class)->except(['create', 'edit', ' update', 'destroy']);


Route::middleware('guest')->group(function () {

    Route::get('/register', [RegisterController::class, 'register'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
    Route::get('/login', [LoginController::class, 'login'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate'])->name('login.authenticate');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');
Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update')->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/bookmarks', [BookmarkController::class, 'index'])->name('bookmarks.index');
    Route::post('/bookmarks/{job}', [BookmarkController::class, 'store'])->name('bookmarks.store');
    Route::delete('/bookmarks/{job}', [BookmarkController::class, 'destroy'])->name('bookmarks.destroy');
});

Route::post('/jobs/{job}/apply', [ApplicantController::class, 'store'])
    ->name('applicant.store')->middleware('auth');
Route::delete('/applicants/{applicant}', [ApplicantController::class, 'destroy'])
    ->name('applicant.destroy')->middleware('auth');


Route::get('/geocode', [GeocodeController::class, 'geocode']);
























// Route::get('/test', function () {

//     $url = route('jobs');

//     return "<a href='$url'> Click here </a>";
// });


// Route::get('/api/users',  function () {
//     return [
//         'name ' => 'masoud',
//         'email' => 'masoud@gmail.com'
//     ];
// });


// Route::get('/posts/{id}', function (string $id) {

//     return 'Post ' . $id;
// });
// Route::get('/users/{id}', function (string $id) {

//     return 'User ' . $id;
// });



// Route::get('/posts/{id}/comments/{commentId}', function (string $id, string $commentId) {

//     return 'Post ' . $id . 'Comment ' . $commentId;
// });


// Route::get('/test', function (Request $request) {

//     return [
//         'method' => $request->method(),
//         'url' => $request->url(),
//         'path' => $request->path(),
//         'fullUrl' => $request->fullUrl(),
//         'ip' => $request->ip(),
//         'userAgent' => $request->userAgent(),
//         'header' => $request->header(),

//     ];
// });

// Route::get('users', function (Request $request) {

//     return $request->except(['name']);
// });


// Route::get('/test', function () {

//     return response('Hello World', 200)->cookie('name', 'masoud');
// });

// Route::get('/download', function () {
//     return response()->download(public_path('favicon.ico'));
// });


// Route::get('/read-cookie', function (Request $request) {

//     $cokkieValue = $request->cookie();
//     return response()->json(['cookie' => $cokkieValue]);
// });
