<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Um blog sobre programação" />
    <meta name="author" content="Lucas Reolon" />
    <title>Code BR - Um blog sobre programação</title>

    <link href="{{ asset('images/favicon.png') }}" rel="icon">
    <link href="{{ asset('images/favicon.png') }}" rel="apple-touch-icon">

    <!-- Tags OG -->
    <meta property="og:locale" content="pt-br" />
    <meta property="og:type" content="blog" />
    <meta property="og:url" content="https://codebr.net" />
    <meta property="og:title" content="Code BR - Um blog sobre programação" />
    <meta property="og:description" content="Um blog sobre programação" />
    <meta property="og:image" content="{{ asset('images/favicon.png') }}" />
    <meta property="og:site_name" content="Code BR - Um blog sobre programação" />
    <!-- Tags OG -->

    <!-- Tags Twitter Card para redes sociais -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Code BR - Um blog sobre programação">
    <meta name="twitter:description" content="Um blog sobre programação">
    <meta name="twitter:image" content="{{ asset('icons/favicon') }}">
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

    <link type="text/css" href="https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800" rel="stylesheet">

    <link href="{{ asset('css/app-critical.css') }}" rel="stylesheet">
</head>

<body>
    @include('components.menu')

    <section class="hero is-medium is-dark is-bold" style="background-image: url('{{ asset('images/background.webp') }}');">
        <div class="hero-body">
            <div class="container text-center">
                <h1 class="title">
                    Code BR
                </h1>
                <h2 class="subtitle" id="subtitulo">
                </h2>
            </div>
        </div>
    </section>

    <section class="section index">
        <div class="container">
            @if (!request()->has('q'))
                <h2 class="title is-3">Artigos em Destaque</h2>
                <div class="columns is-multiline">
                    @foreach ($artigosDestaque as $artigo)
                        @include('components.bloco-pequeno-artigo')
                    @endforeach
                </div>
            @endif

            @if (request()->has('q'))
                <h2 class="title is-3">Resultados da busca</h2>
            @else
                <h2 class="title is-3">Artigos Recentes</h2>
            @endif
            <div class="columns is-multiline">
                @foreach ($artigos as $artigo)
                    @include('components.bloco-pequeno-artigo')
                @endforeach
            </div>

            <div class="container">
                <?php
                $totalPages = ceil($count / $artigosPorPagina);
                $pagesToShow = 2;
                $searchQuery = request('q') ? '&q=' . urlencode(request('q')) : '';
                ?>
                <nav class="pagination is-centered" role="navigation" aria-label="pagination">
                    @if ($numeroPagina > 1)
                        <a class="pagination-previous" href="{{ url('/page/' . ($numeroPagina - 1) . '?q=' . urlencode(request('q'))) }}">Anterior</a>
                    @endif

                    <ul class="pagination-list">
                        @for ($i = max(1, $numeroPagina - $pagesToShow); $i <= min($totalPages, $numeroPagina + $pagesToShow); $i++)
                            <li>
                                <a class="pagination-link {{ $i == $numeroPagina ? 'is-current' : '' }}" href="{{ url('/page/' . $i . '?q=' . urlencode(request('q'))) }}">
                                    {{ $i }}
                                </a>
                            </li>
                        @endfor
                    </ul>

                    @if ($numeroPagina < $totalPages)
                        <a class="pagination-next" href="{{ url('/page/' . ($numeroPagina + 1) . '?q=' . urlencode(request('q'))) }}">Próxima</a>
                    @endif
                </nav>
            </div>
        </div>
    </section>

    @include('components.rodape')
</body>

<link href="{{ asset('css/app.css') }}" rel="stylesheet">
<script src="{{ asset('js/bundle.js') }}"></script>

<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-3072419723964148" crossorigin="anonymous"></script>

</html>
