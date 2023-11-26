<!DOCTYPE html>
<html lang="{{ $artigo->lang }}">

<head>
    <meta charset="UTF-8">
    <title>{{ $artigo->html_title ? $artigo->html_title : 'Code BR - Um blog sobre programação' }}</title>
    <meta name="robots" content="index, follow">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ $artigo->html_meta }}" />
    <meta name="author" content="Lucas Reolon" />
    <link href="{{ asset('images/favicon.png') }}" rel="icon">
    <link href="{{ asset('images/favicon.png') }}" rel="apple-touch-icon">
    <link href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css" rel="stylesheet">
    <link type="text/css" href="https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Lora" rel="stylesheet">

    <!-- Tags OG -->
    <meta property="og:locale" content="pt-br" />
    <meta property="og:type" content="artigo" />
    <meta property="og:title" content="{{ $artigo->html_title ? $artigo->html_title : 'Code BR - Um blog sobre programação' }}" />
    <meta property="og:description" content="{{ $artigo->html_meta ? $artigo->html_meta : 'Um blog sobre programação' }}" />
    <meta property="og:url" content="{{ 'http://codebr.net/artigo/' . $artigo->url }}" />
    <meta property="og:image" content="{{ isset($artigo->{"imagem"}) ? asset($artigo->imagem) : asset('images/favicon.png') }}" />
    <meta property="og:site_name" content="Code BR - Um blog sobre programação" />
    <!-- Tags OG -->

    <!-- Tags Twitter Card para redes sociais -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $artigo->html_title }}">
    <meta name="twitter:description" content="{{ $artigo->html_meta ? $artigo->html_meta : 'Um blog sobre programação' }}">
    <meta name="twitter:image" content="{{ isset($artigo->{"imagem"}) ? asset($artigo->imagem) : asset('images/favicon.png') }}">
    <!-- Tags Twitter Card para redes sociais -->

    <!-- Google analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-WMB4K5EVCL"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-WMB4K5EVCL');
    </script>
    <!-- Google analytics -->

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="{{ asset('js/script.js') }}"></script>
</head>

<body>
    @include('components.menu')

    <section class="hero is-medium is-dark is-bold" style="background-image: url('{{ asset('images/background.webp') }}');">
        <div class="hero-body">
            <div class="container text-center">
                <div class="columns is-centered">
                    <div class="column is-two-thirds">
                        <h1 class="title artigo">
                            {!! $artigo->artigo !!}
                        </h1>
                        <h2 class="subtitle">
                            {!! $artigo->descricao !!}
                        </h2>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="container">
        <div class="section artigo">
            <div class="columns is-centered">
                <div class="column is-one">
                    <script type="text/javascript">
                        atOptions = {
                            'key': '19b4f71efec5db23c79bcb62b7c1c696',
                            'format': 'iframe',
                            'height': 600,
                            'width': 160,
                            'params': {}
                        };
                        document.write('<scr' + 'ipt type="text/javascript" src="//tubfurryhen.com/19b4f71efec5db23c79bcb62b7c1c696/invoke.js"></scr' + 'ipt>');
                    </script>
                </div>
                <div class="column is-two-thirds">
                    {!! $artigo->texto !!}
                </div>
                <div class="column is-one">
                    <script type="text/javascript">
                        atOptions = {
                            'key': '19b4f71efec5db23c79bcb62b7c1c696',
                            'format': 'iframe',
                            'height': 600,
                            'width': 160,
                            'params': {}
                        };
                        document.write('<scr' + 'ipt type="text/javascript" src="//tubfurryhen.com/19b4f71efec5db23c79bcb62b7c1c696/invoke.js"></scr' + 'ipt>');
                    </script>
                </div>
            </div>
        </div>

        {{-- <div class="section">
            <div class="columns">
                <div class="column">
                    <h2 class="title is-3">Artigos em Destaque</h2>
                    <div class="columns is-multiline">
                        @foreach ($artigosSugeridos as $artigo)
                            @include('components.bloco-pequeno-artigo')
                        @endforeach
                    </div>
                </div>
            </div>
        </div> --}}
    </div>

    <div class="section comentarios">
        <div class="container">
            <div class="columns is-centered">
                <div class="column is-two-thirds">
                    <h2 class="title is-3">Comentários</h2>
                    <div class="message">
                        <div class="message-body">
                            <form id="comentarioForm" method="POST">
                                <div class="field">
                                    <p class="label">Nome:</p>
                                    <div class="control">
                                        <input class="input" name="nome" type="text" required>
                                    </div>
                                </div>
                                <div class="field">
                                    <p class="label">Email (não será publicado):</p>
                                    <div class="control">
                                        <input class="input" name="email" type="email">
                                    </div>
                                </div>
                                <div class="field">
                                    <p class="label">Comentário:</p>
                                    <div class="control">
                                        <textarea class="textarea" name="comentario" required></textarea>
                                    </div>
                                </div>
                                <div class="field">
                                    <div class="g-recaptcha" data-sitekey="6LeJkxwpAAAAAG3I6h3cfBxaVFqMC4NHpCVc0sw8"></div>
                                </div>
                                <div class="control">
                                    <button class="button is-primary" type="submit">Enviar Comentário</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="bloco-comentarios">
                        <div class="box">
                            <article class="media">
                                <div class="media-content">
                                    <div class="content">
                                        <strong>
                                            <p>Nome do Autor 1</p>
                                        </strong>
                                        <p>
                                            Comentário 1 Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer
                                            posuere erat a ante.
                                        </p>
                                    </div>
                                </div>
                            </article>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type='text/javascript' src='//tubfurryhen.com/b9/15/02/b915027095387ef94f23fad1d39276c3.js'></script>

    @include('components.rodape')
</body>
<script>var exports = {};</script>

<script src="https://www.google.com/recaptcha/api.js"></script>
<script src="{{ asset('js/comentarios.js') }}"></script>
<script src="{{ asset('js/app.js') }}"></script>

</html>
