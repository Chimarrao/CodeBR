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

Route::get('/artigo', [ArtigoController::class, 'get']);
Route::get('/artigos', [ArtigoController::class, 'getAll']);
Route::get('/artigos-destaque', [ArtigoController::class, 'getAllDestaque']);

Route::post('/contato', [ContatoController::class, 'post']);
Route::post('/comentarios', [ComentarioController::class, 'post']);
