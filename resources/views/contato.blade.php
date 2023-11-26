<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Contato" />
    <meta name="author" content="Lucas Reolon" />
    <title>Code BR - Contato</title>
    <link href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css" rel="stylesheet">

    <link href="{{ asset('images/favicon.png') }}" rel="icon">
    <link href="{{ asset('images/favicon.png') }}" rel="apple-touch-icon">

    <!-- Tags OG -->
    <meta property="og:locale" content="pt-br" />
    <meta property="og:type" content="blog" />
    <meta property="og:url" content="https://codebr.net" />
    <meta property="og:title" content="Code BR - Contato" />
    <meta property="og:description" content="Contato" />
    <meta property="og:image" content="{{ asset('images/favicon.png') }}" />
    <meta property="og:site_name" content="Code BR - Contato" />
    <!-- Tags OG -->

    <!-- Tags Twitter Card para redes sociais -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Code BR - Contato">
    <meta name="twitter:description" content="Contato">
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

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="{{ asset('js/script.js') }}"></script>
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


    @include('components.rodape')
</body>

</html>
