<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/artigos', 'App\Http\Controllers\ArtigoController@get');
Route::get('/artigos-destaque', 'App\Http\Controllers\ArtigoController@destaque');

Route::post('/contato', 'App\Http\Controllers\ContatoController@post');
Route::post('/comentarios', 'App\Http\Controllers\ComentarioController@post');