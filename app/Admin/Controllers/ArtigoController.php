<?php

namespace App\Admin\Controllers;

use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Layout\Content;
use App\Models\Artigo;
use App\Models\ArtigoIdioma;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ArtigoController extends AdminController
{
    protected $title = 'Artigos';

    protected function grid()
    {
        $grid = new Grid(new Artigo());

        $grid->model()
            ->leftJoin('artigos_idiomas as ai', 'artigos.id_artigo', '=', 'ai.id_artigo')
            ->select('artigos.*', DB::raw("
                CASE 
                    WHEN ai.id_ligacao IS NOT NULL 
                        AND artigos.id_artigo <> (
                            SELECT MIN(a2.id_artigo)
                            FROM artigos a2 
                            JOIN artigos_idiomas ai2 ON a2.id_artigo = ai2.id_artigo 
                            WHERE ai2.id_ligacao = ai.id_ligacao
                        )
                    THEN 1
                    ELSE 0
                END as filho
            "))
            ->orderByRaw("
                COALESCE(
                    (SELECT MIN(a2.id_artigo)
                    FROM artigos a2 
                    JOIN artigos_idiomas ai2 ON a2.id_artigo = ai2.id_artigo 
                    WHERE ai2.id_ligacao = ai.id_ligacao
                    ),
                    artigos.id_artigo
                ) DESC,
                filho ASC,
                artigos.id_artigo DESC
            ");


        $grid->imagem('Imagem')->display(function ($imagem) {
            if ($this->filho == 1) {
                $idArtigo = $this->id_artigo;
                return $imagem ?
                    "
                <style>
                    tr[data-key='$idArtigo'] {
                        background: linear-gradient(
                            to right,
                            transparent 10%, /* Primeiros 10% sem cor (transparente) */
                          #dfe8fd 10% /* Os 90% restantes com a cor desejada */
                        );
                    }
                </style>
                "
                    .
                    '<img src="' . asset($imagem) . '" style="width: 15rem; object-fit: cover; border-radius: 5px;; margin-left: 15rem">' : 'Sem imagem';
            }
            return $imagem ? '<img src="' . asset($imagem) . '" style="width: 30rem; object-fit: cover; border-radius: 5px">' : 'Sem imagem';
        })->style('text-align: center;');

        $grid->column('id_artigo', 'ID');

        $grid->column('artigo', 'Título')->display(function ($artigo) {
            if ($this->filho == 1) {
                return '<span style="padding-left: 20px;">↳ ' . $artigo . '</span>';
            }
            return $artigo;
        });

        $grid->column('liberado', 'Liberado')->display(function ($liberado) {
            return $liberado ? '✅' : '❌';
        });

        $grid->column('destaque', 'Destaque')->display(function ($destaque) {
            return $destaque ? 'Sim' : 'Não';
        });

        $grid->column('url', 'URL');
        $grid->column('data_criacao', 'Data de Criação');
        $grid->column('data_publicacao', 'Data de Publicação');
        $grid->column('data_modificacao', 'Data de Modificação');

        $grid->column('lang', 'Idioma')->display(function ($lang) {
            return $lang;
        });

        return $grid;
    }

    protected function form()
    {

        $form = new Form(new Artigo());
        $githubToken = env('GITHUB_TOKEN');

        $form->display('id_artigo', 'ID');

        $form->text('artigo', 'Título');

        $form->switch('liberado', 'Liberado')->default(0);
        $form->switch('destaque', 'Destaque')->default(0);

        $form->text('descricao', 'Descrição');

        $form->html('<button type="button" class="btn btn-primary" onclick="padronizarTextoTMEditor()">Padronizar</button> ' . $this->getScriptPadronizacao());

        $form->html('
            <div class="alert alert-info" style="margin-bottom: 20px;">
                <strong>Atenção:</strong>
                <ul>
                    <li>Para inserir um bloco de código:</li>
                        &lt;pre&gt;
                            &lt;code class=&quot;language-linguagem&quot;&gt;
                            &lt;/code&gt;
                        &lt;/pre&gt;
                    <li>Para colocar gráficos:</li>
                    1) Adicione um chart: <br>
                    &lt;canvas id=&quot;chartSenior&quot;&gt;&lt;/canvas&gt; <br>
                    2) Coloque a importação do chartjs <br>
                    &lt;script src=&quot;https://cdn.jsdelivr.net/npm/chart.js&quot;&gt;&lt;/script&gt; <br>
                    3) Coloque o script conforme a necessidade
                </ul>
            </div>
        ');

        $form->tmeditor('texto', 'Texto');

        $form->image('imagem', 'Imagem')->disk('public_images')->name(function ($file) use ($form, $githubToken) {
            $url = Request::url();
            $isEditing = strpos($url, '/edit') !== false;

            if (!$isEditing) {
                $id = Request::segment(3);
                $artigo = Artigo::findOrFail($id);
                $nomeArtigo = $artigo->artigo;
            } else {
                $nomeArtigo = $form->input('artigo');
            }

            $nomeArtigo = Str::slug('imagem ' . $nomeArtigo);

            if (strlen($nomeArtigo) > 55) {
                $nomeArtigo = substr($nomeArtigo, 0, 55);
            }

            $uniqid = uniqid();

            $nomeImagem = $nomeArtigo . '-' . $uniqid . '.webp';
            $caminhoOriginal = $file->getPath() . '/' . $file->getFilename();
            $imagem = Image::make($caminhoOriginal);

            // Gerar a imagem de 700px
            $imagem->resize(700, null, function ($constraint) {
                $constraint->aspectRatio();
            });

            $imagem->encode('webp', 80)->save(public_path('images/' . $nomeImagem));
            $caminhoImagem = public_path('images/' . $nomeImagem);

            // Criar a imagem menor (392px de largura)
            $nomeImagemPequena = $nomeArtigo . '-' . $uniqid . '.webp';
            $imagemPequena = Image::make($caminhoOriginal);

            $imagemPequena->resize(392, null, function ($constraint) {
                $constraint->aspectRatio();
            });

            $imagemPequena->encode('webp', 80)->save(public_path('images/temp/' . $nomeImagemPequena));
            $caminhoImagemPequena = public_path('images/temp/' . $nomeImagemPequena);

            $nomeDoDonoGithub = 'Chimarrao';
            $nomeDoRepositorio = 'CodeBR-img';
            $branch = 'img';

            // Enviar a imagem maior ao GitHub
            if (file_exists($caminhoImagem)) {
                $imageContent = base64_encode(file_get_contents($caminhoImagem));

                $apiUrl = "https://api.github.com/repos/{$nomeDoDonoGithub}/{$nomeDoRepositorio}/contents/images/{$nomeImagem}";

                $response = Http::withHeaders([
                    'Authorization' => "token {$githubToken}",
                    'Accept' => 'application/vnd.github.v3+json',
                ])->put($apiUrl, [
                    'message' => "Upload da imagem {$nomeImagem}",
                    'content' => $imageContent,
                    'branch' => $branch,
                ]);

                if ($response->failed()) {
                    Log::error('Falha ao enviar a imagem maior para o GitHub: ' . $response->body());
                }
            }

            // Enviar a imagem menor ao GitHub
            if (file_exists($caminhoImagemPequena)) {
                $imageContentPequena = base64_encode(file_get_contents($caminhoImagemPequena));

                $apiUrlPequena = "https://api.github.com/repos/{$nomeDoDonoGithub}/{$nomeDoRepositorio}/contents/images/pequenas/{$nomeImagemPequena}";

                $responsePequena = Http::withHeaders([
                    'Authorization' => "token {$githubToken}",
                    'Accept' => 'application/vnd.github.v3+json',
                ])->put($apiUrlPequena, [
                    'message' => "Upload da imagem pequena {$nomeImagemPequena}",
                    'content' => $imageContentPequena,
                    'branch' => $branch,
                ]);

                if ($responsePequena->failed()) {
                    Log::error('Falha ao enviar a imagem pequena para o GitHub: ' . $responsePequena->body());
                }

                // Apagar a imagem temporária
                unlink($caminhoImagemPequena);
            }

            return 'https://cdn.statically.io/gh/Chimarrao/CodeBR-img/img/images/' . $nomeImagem;
        });

        $form->saving(function (Form $form) {
            $form->model()->html_title = $form->input('artigo');
            $form->model()->html_meta = $form->input('descricao');
        });

        $form->text('url', 'URL');
        $form->text('autor', 'Autor')->default(auth()->user()->name);

        $form->date('data_criacao', 'Data de Criação')->default(now());
        $form->date('data_publicacao', 'Data de Publicação')->default(now());
        $form->date('data_modificacao', 'Data de Modificação')->default(now());

        $form->text('lang', 'Idioma')->default('pt-br');
        $form->text('tags', 'Tags');

        $url = Request::url();
        $isEditing = strpos($url, '/edit') !== false;
        $vinculacao = false;

        if ($isEditing) {
            $id_artigo = Request::segment(3);
            $vinculacao = ArtigoIdioma::where('id_artigo', $id_artigo)->exists();

            $artigo = Artigo::findOrFail($id_artigo);
            $nomeArtigo = $artigo->artigo;
        }

        $itensOptions = [
            'novo'     => 'Criar novo grupo',
            'vincular' => 'Vincular a grupo existente',
        ];

        if ($vinculacao) {
            $itensOptions['manter'] = 'Manter no grupo: ' . $nomeArtigo;
        }

        $form->radio('grupo_opcao', 'Opção de Grupo')
            ->options($itensOptions)
            ->default($vinculacao ? 'manter' : 'novo')
            ->help('Escolha se deseja criar um novo grupo ou vincular este artigo a um grupo existente.')
            ->when('vincular', function (Form $form) {
                $form->select('id_ligacao', 'Grupo Existente')
                    ->options(function () {
                        $grupos = ArtigoIdioma::select('id_ligacao')
                            ->groupBy('id_ligacao')
                            ->get();
                        $options = [];
                        foreach ($grupos as $grupo) {
                            $registros = ArtigoIdioma::where('id_ligacao', $grupo->id_ligacao)->orderByDesc('id_artigo')->get();

                            foreach ($registros as $reg) {
                                $artigo = Artigo::find($reg->id_artigo);

                                $idiomas = [];
                                $nomeArtigo = $artigo->artigo;

                                if ($artigo) {
                                    $idiomas[] = $artigo->lang;
                                }
                            }

                            $options[$grupo->id_ligacao] = $nomeArtigo . ' | ' . implode(' | ', $idiomas);
                        }
                        return $options;
                    })
                    ->help('Selecione um grupo existente para vincular este artigo, se desejar.')
                    ->rules('nullable');
            });

        $form->ignore(['grupo_opcao', 'id_ligacao']);

        $form->switch('excluido', 'Excluído')->default(0);

        $form->saved(function (Form $form) {
            $grupoOpcao = request()->input('grupo_opcao');
            $selectedGrupo = request()->input('id_ligacao');
            $artigoId = $form->model()->id_artigo;

            if ($grupoOpcao === 'novo') {
                $novoGrupo = (string) Str::uuid();
                \App\Models\ArtigoIdioma::updateOrCreate(
                    ['id_artigo' => $artigoId],
                    ['id_ligacao' => $novoGrupo]
                );
            } elseif ($grupoOpcao === 'vincular' && !empty($selectedGrupo)) {
                \App\Models\ArtigoIdioma::updateOrCreate(
                    ['id_artigo' => $artigoId],
                    ['id_ligacao' => $selectedGrupo]
                );
            }
        });


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

    private function getScriptPadronizacao()
    {
        return file_get_contents(__DIR__ . '/../includes/script-padronizacao.blade.php');
    }
}
