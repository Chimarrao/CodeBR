<?php

namespace App\Admin\Controllers;

use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Layout\Content;
use App\Models\Comentario;
use App\Models\Artigo;

class ComentarioController extends AdminController
{
    protected $title = 'Comentários';

    protected function grid()
    {
        $grid = new Grid(new Comentario());

        $grid->model()->orderBy('id', 'desc');

        $grid->column('id', 'ID')->sortable();
        $grid->column('nome', 'Nome');
        $grid->column('id_artigo', 'Artigo')->display(function ($idArtigo) {
            $artigo = Artigo::find($idArtigo);
            return $artigo ? $artigo->artigo : 'Artigo não encontrado';
        });
        $grid->column('email', 'Email');
        $grid->column('comentario', 'Comentário')->limit(50);
        $grid->column('data', 'Data')->display(function ($data) {
            return \Carbon\Carbon::parse($data)->format('d/m/Y H:i');
        });
        $grid->column('id_comentario_resposta', 'Resposta ao Comentário ID');
        $grid->column('excluido', 'Excluído')->display(function ($excluido) {
            return $excluido ? 'Sim' : 'Não';
        });

        $grid->filter(function($filter){
            $filter->like('nome', 'Nome');
            $filter->like('email', 'Email');
            $filter->equal('id_artigo', 'ID Artigo');
            $filter->equal('excluido', 'Excluído')->select([0 => 'Não', 1 => 'Sim']);
        });

        $grid->actions(function ($actions) {
            $actions->disableEdit();
            $actions->disableDelete();
        });

        return $grid;
    }

    protected function form()
    {
        $form = new Form(new Comentario());

        $form->display('id', 'ID');
        $form->text('nome', 'Nome')->required();
        $form->select('id_artigo', 'Artigo')->options(Artigo::pluck('artigo', 'id_artigo'))->required();
        $form->email('email', 'Email')->required();
        $form->textarea('comentario', 'Comentário')->required();
        $form->datetime('data', 'Data')->default(now())->required();
        $form->number('id_comentario_resposta', 'ID do Comentário de Resposta')->default(0)->help('Se este comentário for uma resposta a outro, informe aqui o ID do comentário principal.');
        $form->switch('excluido', 'Excluído')->states([
            'on'  => ['value' => 1, 'text' => 'Sim', 'color' => 'danger'],
            'off' => ['value' => 0, 'text' => 'Não', 'color' => 'primary'],
        ])->default(0);

        return $form;
    }

    protected function detail($id)
    {
        $show = new Show(Comentario::findOrFail($id));

        $show->field('id', 'ID');
        $show->field('nome', 'Nome');
        $show->field('id_artigo', 'ID do Artigo');
        $show->field('email', 'Email');
        $show->field('comentario', 'Comentário');
        $show->field('data', 'Data')->as(function($data) {
            return \Carbon\Carbon::parse($data)->format('d/m/Y H:i');
        });
        $show->field('id_comentario_resposta', 'ID Comentário Resposta');
        $show->field('excluido', 'Excluído')->as(function ($excluido) {
            return $excluido ? 'Sim' : 'Não';
        });

        return $show;
    }

    public function edit($id, Content $content)
    {
        return $content
            ->header('Editar Comentário')
            ->body($this->form()->edit($id));
    }

    public function create(Content $content)
    {
        return $content
            ->header('Criar Comentário')
            ->body($this->form());
    }
}
