<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JobController;
use App\Http\Controllers\HomeController;


Route::get('/', [HomeController::class, 'index']);
// Route::get('/jobs', [JobController::class, 'index']);
// Route::get('/jobs/create', [JobController::class, 'create']);
// Route::get('/jobs/{id}', [JobController::class, 'show']);
// Route::post('/jobs', [JobController::class, 'store']);


Route::resource('/jobs', JobController::class);


































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
