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
                        <h1>Política de Privacidade</h1>
                        <span class="subheading" id="subtitulo"></span>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <main class="mb-4">
        <div class="container px-4 px-lg-5">
            <div class="row gx-4 gx-lg-5 justify-content-center">
                <div class="col-md-10 col-lg-8 col-xl-7">
                    <h2><span style="color: rgb(68, 68, 68);">Política Privacidade</span></h2><p><span style="color: rgb(68, 68, 68);">A sua privacidade é importante para nós. É política do CodeBR respeitar a sua privacidade em relação a qualquer informação sua que possamos coletar no site <a href="https://codebr.net/">CodeBR</a>, e outros sites que possuímos e operamos.</span></p><p><span style="color: rgb(68, 68, 68);">Solicitamos informações pessoais apenas quando realmente precisamos delas para lhe fornecer um serviço. Fazemo-lo por meios justos e legais, com o seu conhecimento e consentimento. Também informamos por que estamos coletando e como será usado.</span></p><p><span style="color: rgb(68, 68, 68);">Apenas retemos as informações coletadas pelo tempo necessário para fornecer o serviço solicitado. Quando armazenamos dados, protegemos dentro de meios comercialmente aceitáveis ​​para evitar perdas e roubos, bem como acesso, divulgação, cópia, uso ou modificação não autorizados.</span></p><p><span style="color: rgb(68, 68, 68);">Não compartilhamos informações de identificação pessoal publicamente ou com terceiros, exceto quando exigido por lei.</span></p><p><span style="color: rgb(68, 68, 68);">O nosso site pode ter links para sites externos que não são operados por nós. Esteja ciente de que não temos controle sobre o conteúdo e práticas desses sites e não podemos aceitar responsabilidade por suas respectivas&nbsp;</span><a href="https://politicaprivacidade.com/" rel="noopener noreferrer" target="_blank" style="background-color: transparent; color: rgb(68, 68, 68);">políticas de privacidade</a><span style="color: rgb(68, 68, 68);">.</span></p><p><span style="color: rgb(68, 68, 68);">Você é livre para recusar a nossa solicitação de informações pessoais, entendendo que talvez não possamos fornecer alguns dos serviços desejados.</span></p><p><span style="color: rgb(68, 68, 68);">O uso continuado de nosso site será considerado como aceitação de nossas práticas em torno de privacidade e informações pessoais. Se você tiver alguma dúvida sobre como lidamos com dados do usuário e informações pessoais, entre em contacto connosco.</span></p><p><span style="color: rgb(68, 68, 68);"><ul><li><span style="color: rgb(68, 68, 68);">O serviço Google AdSense que usamos para veicular publicidade usa um cookie DoubleClick para veicular anúncios mais relevantes em toda a Web e limitar o número de vezes que um determinado anúncio é exibido para você.</span></li><li><span style="color: rgb(68, 68, 68);">Para mais informações sobre o Google AdSense, consulte as FAQs oficiais sobre privacidade do Google AdSense.</span></li><li><span style="color: rgb(68, 68, 68);">Utilizamos anúncios para compensar os custos de funcionamento deste site e fornecer financiamento para futuros desenvolvimentos. Os cookies de publicidade comportamental usados ​​por este site foram projetados para garantir que você forneça os anúncios mais relevantes sempre que possível, rastreando anonimamente seus interesses e apresentando coisas semelhantes que possam ser do seu interesse.</span></li><li><span style="color: rgb(68, 68, 68);">Vários parceiros anunciam em nosso nome e os cookies de rastreamento de afiliados simplesmente nos permitem ver se nossos clientes acessaram o site através de um dos sites de nossos parceiros, para que possamos creditá-los adequadamente e, quando aplicável, permitir que nossos parceiros afiliados ofereçam qualquer promoção que pode fornecê-lo para fazer uma compra.</span></li></ul><p><br></p></span></p><h3><span style="color: rgb(68, 68, 68);">Compromisso do Usuário</span></h3><p><span style="color: rgb(68, 68, 68);">O usuário se compromete a fazer uso adequado dos conteúdos e da informação que o CodeBR oferece no site e com caráter enunciativo, mas não limitativo:</span></p><ul><li><span style="color: rgb(68, 68, 68);">A) Não se envolver em atividades que sejam ilegais ou contrárias à boa fé a à ordem pública;</span></li><li><span style="color: rgb(68, 68, 68);">B) Não difundir propaganda ou conteúdo de natureza racista, xenofóbica, </span><span style="color: rgb(33, 37, 41);"><a href='https://apostasonline.guru/bet365-apostas/' style='color:inherit !important; text-decoration: none !important; font-size: inherit !important;'>bet365 brasil</a></span><span style="color: rgb(68, 68, 68);"> ou azar, qualquer tipo de pornografia ilegal, de apologia ao terrorismo ou contra os direitos humanos;</span></li><li><span style="color: rgb(68, 68, 68);">C) Não causar danos aos sistemas físicos (hardwares) e lógicos (softwares) do CodeBR, de seus fornecedores ou terceiros, para introduzir ou disseminar vírus informáticos ou quaisquer outros sistemas de hardware ou software que sejam capazes de causar danos anteriormente mencionados.</span></li></ul><h3><span style="color: rgb(68, 68, 68);">Mais informações</span></h3><p><span style="color: rgb(68, 68, 68);">Esperemos que esteja esclarecido e, como mencionado anteriormente, se houver algo que você não tem certeza se precisa ou não, geralmente é mais seguro deixar os cookies ativados, caso interaja com um dos recursos que você usa em nosso site.</span></p><p><span style="color: rgb(68, 68, 68);">Esta política é efetiva a partir de&nbsp;15 April 2023 00:52</span></p>
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
                            <a href1="#">
                                <span class="fa-stack fa-lg">
                                    <i class="fas fa-circle fa-stack-2x"></i>
                                    <i class="fab fa-twitter fa-stack-1x fa-inverse"></i>
                                </span>
                            </a>
                        </li>
                        <li class="list-inline-item">
                            <a href1="#">
                                <span class="fa-stack fa-lg">
                                    <i class="fas fa-circle fa-stack-2x"></i>
                                    <i class="fab fa-facebook-f fa-stack-1x fa-inverse"></i>
                                </span>
                            </a>
                        </li>
                        <li class="list-inline-item">
                            <a href1="#">
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
    <script src="https://use.fontawesome.com/releases/v5.15.3/js/all.js" crossorigin="anonymous"></script>
</body>

</html>