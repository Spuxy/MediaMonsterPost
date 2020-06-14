<?php

use App\PostOffice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
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
