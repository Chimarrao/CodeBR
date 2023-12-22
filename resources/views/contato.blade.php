<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Contato" />
    <meta name="author" content="Lucas Reolon" />
    <title>Code BR - Contato</title>

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

    <section>
        <div class="container">
            <div class="columns is-centered">
                <div class="column is-two-thirds">
                    <div class="message-body mt-6">
                        <form id="contatoForm" method="POST">
                            <div class="field">
                                <p class="label">Nome:</p>
                                <div class="control">
                                    <input class="input" name="nome" type="text" required>
                                </div>
                            </div>
                            <div class="field">
                                <p class="label">Email:</p>
                                <div class="control">
                                    <input class="input" name="email" type="email">
                                </div>
                            </div>
                            <div class="field">
                                <p class="label">Telefone:</p>
                                <div class="control">
                                    <input class="input" name="telefone" type="phone">
                                </div>
                            </div>
                            <div class="field">
                                <p class="label">Mensagem:</p>
                                <div class="control">
                                    <textarea class="textarea" name="mensagem" required></textarea>
                                </div>
                            </div>
                            <div class="field">
                                <div class="g-recaptcha" data-sitekey="6LdlmB4pAAAAAF8uCw8BeWogDClVSiCRx5eNx-7e"></div>
                            </div>
                            <div class="control">
                                <button class="button is-primary" type="submit">Enviar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('components.rodape')
</body>

<link href="{{ asset('css/app.css') }}" rel="stylesheet">
<script src="{{ asset('js/bundle.js') }}"></script>

<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-3072419723964148" crossorigin="anonymous"></script>

</html>
