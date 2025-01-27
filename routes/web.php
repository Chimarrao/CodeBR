<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImageConverterController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', 'App\Http\Controllers\IndexController@index');
Route::get('/page/{numeroPagina}', 'App\Http\Controllers\IndexController@index');

Route::get('/artigo/{slug}', 'App\Http\Controllers\ArtigoController@artigo');

Route::get('/media/{image}', 'App\Http\Controllers\MediaController@get');

Route::get('/sobre', function() {
    return view('sobre');
});

Route::get('/contato', function() {
    return view('contato');
});

Route::get('/politica-de-privacidade', function() {
    return view('politica-de-privacidade');
});

Route::get('/converter/', function() {
    return view('converter');
});

Route::get('/converter/{formato?}', function() {
    return view('converter');
});

Route::get('/file/{filename}', function ($filename) {
    $path = storage_path('app/public/converted/' . $filename);

    if (!file_exists($path)) {
        abort(404);
    }

    return response()->file($path);
});