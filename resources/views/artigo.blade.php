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

    <link href="{{ asset('css/app-critical.css') }}" rel="stylesheet">
</head>

<body>
    @include('components.menu')

    <section class="hero is-medium is-dark is-bold">
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

    <section class="section artigo">
        <div class="container">
            <div class="columns is-centered">
                <div class="column is-one">
                    {{--  --}}
                </div>
                <div class="column is-two-thirds">
                    {!! $artigo->texto !!}
                </div>
                <div class="column is-one">
                    {{--  --}}
                </div>
            </div>
        </div>
    </section>

    <div class="section comentarios pt-0">
        <div class="container">
            <div class="columns is-centered">
                <div class="column is-two-thirds">
                    <h2 class="title is-3">Comentários</h2>

                    <div class="message">
                        @include('components.comments.form-comentario')
                    </div>

                    <div class="bloco-comentarios">
                        @foreach ($comentarios as $comentario)
                            @include('components.comments.comentario')
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('components.rodape')
</body>

<link href="{{ asset('css/app.css') }}" rel="stylesheet">
<script src="{{ asset('js/bundle.js') }}"></script>
<script src="{{ asset('js/script.js') }}"></script>
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-3072419723964148" crossorigin="anonymous"></script>
<script src="https://www.google.com/recaptcha/api.js"></script>

</html>
