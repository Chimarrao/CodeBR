<?php

namespace App\Admin\Controllers;

use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Layout\Content;
use App\Models\Artigo;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

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
        $form->textarea('texto', 'Texto');

        $form->image('imagem', 'Imagem')->disk('public_images')->name(function ($file) use ($form) {
            $tituloArtigo = $form->input('artigo');
            $nomeImagem = Str::slug($tituloArtigo) . '-' . uniqid() . '.webp';

            $caminhoOriginal = $file->getPath() . '/' . $file->getFilename();

            $imagem = Image::make($caminhoOriginal);

            $imagem->resize(700, null, function ($constraint) {
                $constraint->aspectRatio();
            });

            $imagem->save(public_path('images/' . $nomeImagem), 80, 'webp');

            return $nomeImagem;
        });

        $form->text('url', 'URL');
        $form->text('autor', 'Autor')->default(auth()->user()->name);
        $form->date('data_criacao', 'Data de Criação')->default(now());
        $form->date('data_publicacao', 'Data de Publicação')->default(now());
        $form->text('lang', 'Idioma')->default('pt-br');
        $form->text('tags', 'Tags');
        $form->switch('excluido', 'Excluído')->default(0);

        $form->ignore(['html_title', 'html_meta', 'data_modificacao']);

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
        $show->field('texto', 'Texto')->as(function ($texto) {
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
}
