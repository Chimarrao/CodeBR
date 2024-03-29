<?php

namespace App\Admin\Controllers;

use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Layout\Content;
use App\Models\Artigo;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Request;

class ArtigoController extends AdminController
{
    protected $title = 'Artigos';

    protected function grid()
    {
        $grid = new Grid(new Artigo());
        $grid->id_artigo('ID');
        $grid->artigo('Título');
        $grid->liberado('Liberado')->display(function ($liberado) {
            return $liberado ? 'Sim' : 'Não';
        });
        $grid->destaque('Destaque')->display(function ($destaque) {
            return $destaque ? 'Sim' : 'Não';
        });
        $grid->imagem('Imagem');
        $grid->url('URL');
        $grid->autor('Autor');
        $grid->data_criacao('Data de Criação');
        $grid->data_publicacao('Data de Publicação');
        $grid->data_modificacao('Data de Modificação');
        $grid->lang('Idioma')->display(function ($lang) {
            return match ($lang) {
                'pt-br' => '🇧🇷',
                'en-us' => '🇺🇸',
                default => '🏴‍☠️'
            };
        });
        $grid->tags('Tags');
        $grid->excluido('Excluído')->display(function ($excluido) {
            return $excluido ? 'Sim' : 'Não';
        });

        return $grid;
    }

    protected function form()
    {

        $form = new Form(new Artigo());

        $form->display('id_artigo', 'ID');

        $form->text('artigo', 'Título');

        $form->switch('liberado', 'Liberado')->default(1);
        $form->switch('destaque', 'Destaque')->default(0);

        $form->text('descricao', 'Descrição');

        $form->html('<button type="button" class="btn btn-primary" onclick="padronizarTextoTMEditor()">Padronizar</button> ' . $this->getScriptPadronizacao());
        $form->tmeditor('texto', 'Texto');

        $form->image('imagem', 'Imagem')->disk('public_images')->name(function ($file) use ($form) {
            $url = Request::url();
            $isEditing = strpos($url, '/edit') !== false;

            if (!$isEditing) {
                $id = Request::segment(3);
                $artigo = Artigo::findOrFail($id);
                $nomeArtigo = $artigo->artigo;
            } else {
                $nomeArtigo = $form->input('artigo');
            }

            $nomeArtigo = 'imagem-' . strval($nomeArtigo);
            $nomeArtigo = str_replace(' ', '-', $nomeArtigo);
            $nomeArtigo = preg_replace('/[^A-Za-z0-9\-]/', '', $nomeArtigo);

            if (strlen($nomeArtigo) > 55) {
                $nomeArtigo = substr($nomeArtigo, 0, 55);
            }

            $nomeImagem = $nomeArtigo . '.webp';
            $caminhoOriginal = $file->getPath() . '/' . $file->getFilename();
            $imagem = Image::make($caminhoOriginal);

            $imagem->resize(700, null, function ($constraint) {
                $constraint->aspectRatio();
            });

            $imagem->encode('webp', 80)->save(public_path('images/' . $nomeImagem));
            return $nomeImagem;
        });

        $form->saving(function (Form $form) {
            $form->model()->html_title = $form->input('artigo');
            $form->model()->html_meta = $form->input('descricao');
        });

        $form->text('url', 'URL');
        $form->text('autor', 'Autor')->default(auth()->user()->name);

        $form->date('data_criacao', 'Data de Criação')->default(now());
        $form->date('data_publicacao', 'Data de Publicação')->default(now());
        $form->date('data_modificacao', 'Data de Publicação')->default(now());

        $form->text('lang', 'Idioma')->default('pt-br');
        $form->text('tags', 'Tags');
        $form->switch('excluido', 'Excluído')->default(0);
        
        return $form;
    }

    protected function detail($id)
    {
        $show = new Show(Artigo::findOrFail($id));

        $show->field('id_artigo', 'ID');
        $show->field('artigo', 'Título');
        $show->field('liberado', 'Liberado')->as(function ($liberado) {
            return $liberado ? 'Sim' : 'Não';
        });
        $show->field('destaque', 'Destaque')->as(function ($destaque) {
            return $destaque ? 'Sim' : 'Não';
        });
        $show->field('descricao', 'Descrição');
        $show->field('html_title', 'HTML Title');
        $show->field('html_meta', 'HTML Meta');

        $show->field('texto', 'Texto (<pre><code class="language-xxx">)')->as(function ($texto) {
            return strip_tags(nl2br($texto));
        });
        $show->field('imagem', 'Imagem')->image(asset(''), 300, 300);
        $show->field('url', 'URL');
        $show->field('autor', 'Autor');
        $show->field('data_criacao', 'Data de Criação');
        $show->field('data_publicacao', 'Data de Publicação');
        $show->field('data_modificacao', 'Data de Modificação');
        $show->field('lang', 'Idioma');
        $show->field('tags', 'Tags');
        $show->field('excluido', 'Excluído')->as(function ($excluido) {
            return $excluido ? 'Sim' : 'Não';
        });

        return $show;
    }

    public function edit($id, Content $content)
    {
        return $content
            ->header('Editar Artigo')
            ->body($this->form()->edit($id));
    }

    public function create(Content $content)
    {
        return $content
            ->header('Criar Artigo')
            ->body($this->form());
    }

    private function getScriptPadronizacao() {
        return file_get_contents(__DIR__ . '/../includes/script-padronizacao.blade.php');
    }
}
