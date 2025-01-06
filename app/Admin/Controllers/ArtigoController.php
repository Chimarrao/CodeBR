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
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ArtigoController extends AdminController
{
    protected $title = 'Artigos';

    protected function grid()
    {
        $grid = new Grid(new Artigo());

        $grid->model()->orderBy('id_artigo', 'desc');

        $grid->imagem('Imagem')->display(function ($imagem) {
            return $imagem
                ? '<img src="' . asset($imagem) . '" style="width: 250px; object-fit: cover; border-radius: 5px;">'
                : 'Sem imagem';
        })->style('text-align: center;');

        $grid->id_artigo('ID');
        $grid->artigo('Título');
        $grid->liberado('Liberado')->display(function ($liberado) {
            return $liberado ? 'Sim' : 'Não';
        });
        $grid->destaque('Destaque')->display(function ($destaque) {
            return $destaque ? 'Sim' : 'Não';
        });

        $grid->url('URL');
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

    private function getScriptPadronizacao()
    {
        return file_get_contents(__DIR__ . '/../includes/script-padronizacao.blade.php');
    }
}
