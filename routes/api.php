<?php

use App\Http\Controllers\ArtigoController;
use App\Http\Controllers\ContatoController;
use App\Http\Controllers\ComentarioController;
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

<<<<<<< HEAD
Route::get('/artigo', [ArtigoController::class, 'get']);
Route::get('/artigos', [ArtigoController::class, 'getAll']);
Route::get('/artigos-destaque', [ArtigoController::class, 'getAllDestaque']);

Route::post('/contato', [ContatoController::class, 'post']);
Route::post('/comentarios', [ComentarioController::class, 'post']);
=======
Route::get('/artigos', 'App\Http\Controllers\ArtigoController@get');
Route::get('/artigos-destaque', 'App\Http\Controllers\ArtigoController@destaque');

Route::post('/contato', 'App\Http\Controllers\ContatoController@post');
Route::post('/comentarios', 'App\Http\Controllers\ComentarioController@post');
>>>>>>> 3e6576c (Base de códigos para front para Vue.js)
