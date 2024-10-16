<?php

namespace App\Http\Controllers;

class IndexController extends Controller
{
    /**
     * Exibe a página inicial com uma lista de artigos.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('index');
    }
}
