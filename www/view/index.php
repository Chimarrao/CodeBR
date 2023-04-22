<?= "<base href='" . URL_BASE . "www/view/'>"; ?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Um blog sobre programação" />
    <meta name="author" content="Lucas Reolon" />
    <title><?= SITE_NOME ?></title>
    <link rel="icon" type="image/x-icon" href="assets/favicon.png" />
    <link href="https://fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800" rel="stylesheet" type="text/css" />
    <link href="css/styles.css" rel="stylesheet" />
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-WMB4K5EVCL"></script>
    <!-- Tags OG -->
    <meta property="og:locale" content="pt-br" />
    <meta property="og:type" content="artigo" />
    <meta property="og:title" content="<?= $artigo["html_title"] ? $artigo["html_title"] : SITE_NOME ?>" />
    <meta property="og:description" content="<?= $artigo["html_meta"] ? $artigo["html_meta"] : "Um blog sobre programação" ?>" />
    <meta property="og:url" content="<?= "http://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] ?>" />
    <meta property="og:image" content="https://www.codebr.net/www/view/assets/favicon.png" />
    <meta property="og:site_name" content="<?= SITE_NOME ?>" />
    <!-- Tags OG -->
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-WMB4K5EVCL');
    </script>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light" id="mainNav">
        <div class="container px-4 px-lg-5">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                Menu
                <i class="fas fa-bars"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ms-auto py-4 py-lg-0">
                    <li class="nav-item"><a class="nav-link px-lg-3 py-3 py-lg-4" href="<?= URL_BASE ?>">Home</a></li>
                    <li class="nav-item"><a class="nav-link px-lg-3 py-3 py-lg-4" href="<?= URL_BASE ?>sobre">Sobre</a></li>
                    <li class="nav-item"><a class="nav-link px-lg-3 py-3 py-lg-4" href="<?= URL_BASE ?>contato">Contato</a></li>
                    <li class="nav-item"><a class="nav-link px-lg-3 py-3 py-lg-4" href="<?= URL_BASE ?>politica-de-privacidade">Política de Privacidade</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <header class="masthead" style="background-image: url('assets/img/home-bg.webp')">
        <div class="container position-relative px-4 px-lg-5">
            <div class="row gx-4 gx-lg-5 justify-content-center">
                <div class="col-md-10 col-lg-8 col-xl-7">
                    <div class="site-heading">
                        <h1><?= SITE_NOME ?></h1>
                        <span class="subheading" id="subtitulo"></span>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <div class="container px-4 px-lg-5">
        <div class="row gx-4 gx-lg-5 justify-content-center">
            <div class="col-md-10 col-lg-8 col-xl-7">
                <?php foreach ($artigos as $chave => $artigo) { ?>
                    <div class="post-preview" id="artigo_<?= $chave ?>" <?= $chave > 4 ? "style= \"display:none\"" : "" ?>>
                        <a href="<?= URL_BASE ?>artigo/<?= $artigo["url"] ?>">
                            <h2 class="post-title"><?= $artigo["artigo"] ?></h2>
                            <h3 class="post-subtitle"><?= $artigo["descricao"] ?></h3>
                        </a>
                        <p class="post-meta">
                            Publicado por
                            <a><?= $artigo["autor"] ?></a>
                            em
                            <!-- 24 de Setembro de 2021 --> <?= date("d/m/Y", strtotime($artigo["data_publicacao"])) ?>
                        </p>
                    </div>
                    <hr class="my-4" id="linha_artigo_<?= $chave ?>" <?= $chave > 3 ? "style= \"display:none\"" : "" ?> />
                <?php } ?>

                <nav aria-label="">
                    <ul class="pagination justify-content-end pagination-lg">
                        <?php for ($i = 1; $i <= ceil(count((array) $artigos) / 5); $i++) { ?>
                            <li class="page-item" onclick="paginacao('<?= $i ?>')">
                                <a class="page-link" style="cursor: pointer"><?= $i ?></a>
                            </li>
                        <?php } ?>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
    <footer class="border-top">
        <div class="container px-4 px-lg-5">
            <div class="row gx-4 gx-lg-5 justify-content-center">
                <div class="col-md-10 col-lg-8 col-xl-7">
                    <ul class="list-inline text-center">
                        <li class="list-inline-item">
                            <a href1="">
                                <span class="fa-stack fa-lg">
                                    <i class="fas fa-circle fa-stack-2x"></i>
                                    <i class="fab fa-twitter fa-stack-1x fa-inverse"></i>
                                </span>
                            </a>
                        </li>
                        <li class="list-inline-item">
                            <a href1="">
                                <span class="fa-stack fa-lg">
                                    <i class="fas fa-circle fa-stack-2x"></i>
                                    <i class="fab fa-facebook-f fa-stack-1x fa-inverse"></i>
                                </span>
                            </a>
                        </li>
                        <li class="list-inline-item">
                            <a href1="">
                                <span class="fa-stack fa-lg">
                                    <i class="fas fa-circle fa-stack-2x"></i>
                                    <i class="fab fa-github fa-stack-1x fa-inverse"></i>
                                </span>
                            </a>
                        </li>
                    </ul>
                    <div class="small text-center text-muted fst-italic"><?= SITE_NOME ?> <?= VARIAVEIS_ANO ?></div>
                </div>
            </div>
        </div>
    </footer>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/site.js"></script>
    <script src="js/scripts.js"></script>
    <script src="https://use.fontawesome.com/releases/v5.15.3/js/all.js" crossorigin="anonymous"></script>
</body>

</html>