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
    <header class="masthead" style="background-image: url('assets/img/contact-bg.webp')">
        <div class="container position-relative px-4 px-lg-5">
            <div class="row gx-4 gx-lg-5 justify-content-center">
                <div class="col-md-10 col-lg-8 col-xl-7">
                    <div class="page-heading">
                        <h1>Contato</h1>
                        <span class="subheading">Sugestões e xingamentos 😃👍💻</span>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <main class="mb-4">
        <div class="container px-4 px-lg-5">
            <div class="row gx-4 gx-lg-5 justify-content-center">
                <div class="col-md-10 col-lg-8 col-xl-7">
                    <p>Digite aqui seus dados de contato e a mensagem que deseja enviar</p>
                    <div class="my-5">
                        <form method="POST">
                            <div class="form-floating">
                                <input class="form-control" name="nome" type="text" placeholder="Enter your name..." data-sb-validations="required" />
                                <label for="name">Nome</label>
                                <div class="invalid-feedback" data-sb-feedback="name:required">Saber seu nome é importante</div>
                            </div>
                            <div class="form-floating">
                                <input class="form-control" name="email" type="email" placeholder="Enter your email..." data-sb-validations="required,email" />
                                <label for="email">E-mail</label>
                                <div class="invalid-feedback" data-sb-feedback="email:required">Precisamos de um e-mail</div>
                                <div class="invalid-feedback" data-sb-feedback="email:email">O e-mail informado não é valido</div>
                            </div>
                            <div class="form-floating">
                                <input class="form-control" name="telefone" type="tel" placeholder="Enter your phone number..." data-sb-validations1="required" />
                                <label for="phone">Telefone</label>
                                <div class="invalid-feedback" data-sb-feedback1="phone:required"></div>
                            </div>
                            <div class="form-floating">
                                <textarea class="form-control" name="mensagem" placeholder="Enter your message here..." style="height: 12rem" data-sb-validations="required"></textarea>
                                <label for="message">Mensagem</label>
                                <div class="invalid-feedback" data-sb-feedback="message:required">Precisamos saber o motivo do seu contato</div>
                            </div>
                            <br />
                            <div class="d-none" id="submitSuccessMessage">
                                <div class="text-center mb-3">
                                    <div class="fw-bolder">Form submission successful!</div>
                                    To activate this form, sign up at
                                    <br />
                                    <a href="https://startbootstrap.com/solution/contact-forms">https://startbootstrap.com/solution/contact-forms</a>
                                </div>
                            </div>
                            <div class="d-none" id="submitErrorMessage">
                                <div class="text-center text-danger mb-3">Error sending message!</div>
                            </div>
                            <button class="btn btn-primary text-uppercase " id="submitButton" type="submit">Enviar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <footer class="border-top">
        <div class="container px-4 px-lg-5">
            <div class="row gx-4 gx-lg-5 justify-content-center">
                <div class="col-md-10 col-lg-8 col-xl-7">
                    <ul class="list-inline text-center">
                        <li class="list-inline-item">
                            <a href1="#!">
                                <span class="fa-stack fa-lg">
                                    <i class="fas fa-circle fa-stack-2x"></i>
                                    <i class="fab fa-twitter fa-stack-1x fa-inverse"></i>
                                </span>
                            </a>
                        </li>
                        <li class="list-inline-item">
                            <a href1="#!">
                                <span class="fa-stack fa-lg">
                                    <i class="fas fa-circle fa-stack-2x"></i>
                                    <i class="fab fa-facebook-f fa-stack-1x fa-inverse"></i>
                                </span>
                            </a>
                        </li>
                        <li class="list-inline-item">
                            <a href1="#!">
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
    <script src="js/scripts.js"></script>
    <script src="https://cdn.startbootstrap.com/sb-forms-latest.js"></script>
    <script src="https://use.fontawesome.com/releases/v5.15.3/js/all.js" crossorigin="anonymous"></script>
</body>

</html>