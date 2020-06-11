<?php

use Illuminate\Support\Facades\Route;
use App\PostOffice;

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

Route::get('/get-posts', function () {
	return PostOffice::all();
});
Route::get('/get-posts-by-psc/{psc}', function ($psc) {
	return PostOffice::where('psc',$psc)->get();
});
Route::get('/get-posts-by-obec/{obec}', function ($obec) {
	return PostOffice::where('City',$obec)->get();
});
