<?php

namespace App\Admin\Controllers;

use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Layout\Content;
use App\Models\Artigo;
use App\Models\ArtigoCodigo;

class ArtigoCodigoController extends AdminController
{
    protected $title = 'Códigos de Exemplo';

    protected function grid()
    {
        $grid = new Grid(new ArtigoCodigo());

        $grid->id_artigo_codigo('ID');
        $grid->id_artigo('ID do Artigo');
        $grid->id_artigo('Artigo')->display(function ($idArtigo) {
            return Artigo::find($idArtigo)->artigo;
        });
        $grid->language('Language');

        return $grid;
    }

    protected function form()
    {
        $form = new Form(new ArtigoCodigo());

        $form->display('id_artigo_codigo', 'ID');

        $form->select('id_artigo', 'Artigo')->options(Artigo::pluck('artigo', 'id_artigo'))->required();

        $form->textarea('codigo', 'Código')->validator(function ($input) {
            return htmlspecialchars(json_encode($input), ENT_QUOTES, 'UTF-8');
        });
    
        $form->select('language', 'Language')->options([
            'css',
            'clike',
            'javascript',
            'apacheconf',
            'c',
            'csharp',
            'cpp',
            'coffeescript',
            'css-extras',
            'csv',
            'diff',
            'erlang',
            'fortran',
            'go',
            'html',
            'http',
            'hpkp',
            'hsts',
            'java',
            'javadoc',
            'javadoclike',
            'javastacktrace',
            'jsdoc',
            'js-extras',
            'json',
            'json5',
            'jsonp',
            'jsstacktrace',
            'js-templates',
            'markdown',
            'markup',
            'markup-templating',
            'nginx',
            'php',
            'phpdoc',
            'php-extras',
            'plsql',
            'powershell',
            'python',
            'r',
            'jsx',
            'tsx',
            'regex',
            'ruby',
            'rust',
            'sql',
            'typescript',
        ]);

        return $form;
    }

    protected function detail($id)
    {
        $show = new Show(ArtigoCodigo::findOrFail($id));

        $show->field('id_artigo_codigo', 'ID');
        $show->field('id_artigo', 'ID do Artigo');
        $show->field('codigo', 'Código');
        $show->field('language', 'Idioma');

        return $show;
    }

    public function edit($id, Content $content)
    {
        return $content
            ->header('Editar Código de Exemplo')
            ->body($this->form()->edit($id));
    }

    public function create(Content $content)
    {
        return $content
            ->header('Criar Código de Exemplo')
            ->body($this->form());
    }
}
