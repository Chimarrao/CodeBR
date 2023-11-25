<?php

use Illuminate\Routing\Router;
use App\Admin\Controllers\ArtigoController;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {
    $router->get('/', 'HomeController@index')->name('home');
    $router->post('/file_oupload', 'FileUploadController@upload');
    $router->resource('artigos', ArtigoController::class);
    $router->resource('artigos-exemplo', ArtigoCodigoController::class);
});
