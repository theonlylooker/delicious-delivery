<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/',function()
{
	return view('inicio');
});


//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('login/{social}', [App\Http\Controllers\Auth\LoginController::class,'socialLogin'])->where('social','twitter|facebook|google');
Route::get('login/{social}/callback', [App\Http\Controllers\Auth\LoginController::class,'handleProviderCallback'])->where('social','twitter|facebook|google');
Route::get('signup',[App\Http\Controllers\Auth\RegisterController::class,'index']);

Route::get('dashboard',[App\Http\Controllers\DashboardController::class,'index']);
Route::post('signup/new',[App\Http\Controllers\DashboardController::class,'add']);

Route::get('/dashboard',function()
{
	return view('dashboard');
});

Auth::routes();