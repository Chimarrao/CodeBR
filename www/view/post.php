<?= "<base href='" . URL_BASE . "www/view/'>"; ?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="<?= $artigo["html_meta"] ?>" />
    <meta name="author" content="Lucas Reolon" />
    <title><?= $artigo["html_title"] ?></title>
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
    <meta property="og:image" content="https://www.codebr.net/images/favicon.png" />
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

    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-3072419723964148" crossorigin="anonymous"></script>
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
    <header class="masthead" style="background-image: url('assets/img/post-bg.webp')">
        <div class="container position-relative px-4 px-lg-5">
            <div class="row gx-4 gx-lg-5 justify-content-center">
                <div class="col-md-10 col-lg-8 col-xl-7">
                    <div class="post-heading">
                        <h1><?= $artigo["artigo"] ?></h1>
                        <h2 class="subheading"><?= $artigo["descricao"] ?></h2>
                        <span class="meta">
                            Publicado por
                            <a href="#!">Lucas Reolon</a>
                            em <?= date("d/m/Y", strtotime($artigo["data_publicacao"])) ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <article class="mb-4">
        <div class="container px-4 px-lg-5">
            <div class="row gx-4 gx-lg-5 justify-content-center">
                <div class="col-md-10 col-lg-8 col-xl-7">
                    <!-- < ?= $artigo["texto"] ?> -->

                        <?php
                        $anuncio = '
                        <script async="async" data-cfasync="false" src="//pl19080353.highrevenuegate.com/a08dadf016fe2dcbce7ba20664dd5e51/invoke.js"></script>
    <div id="container-a08dadf016fe2dcbce7ba20664dd5e51"></div>
                        ';

                        $anuncio_geral = <<<END
                            <script type="text/javascript">
                                atOptions = {
                                    'key' : '769b5bee2f05501aa04016b08840099f',
                                    'format' : 'iframe',
                                    'height' : 250,
                                    'width' : 300,
                                    'params' : {}
                                };
                                document.write('<scr' + 'ipt type="text/javascript" src="http' + (location.protocol === 'https:' ? 's' : '') + '://www.profitabledisplaynetwork.com/769b5bee2f05501aa04016b08840099f/invoke.js"></scr' + 'ipt>');
                            </script>

                            <script type="text/javascript">
                                atOptions = {
                                    'key' : '769b5bee2f05501aa04016b08840099f',
                                    'format' : 'iframe',
                                    'height' : 250,
                                    'width' : 300,
                                    'params' : {}
                                };
                                document.write('<scr' + 'ipt type="text/javascript" src="http' + (location.protocol === 'https:' ? 's' : '') + '://www.profitabledisplaynetwork.com/769b5bee2f05501aa04016b08840099f/invoke.js"></scr' + 'ipt>');
                            </script>
                        END;

                        ;

                        $count = 0;
                        $html = preg_replace_callback('/<p style="text-align:justify">[\s\t\n]+<strong>(.*?)<\/strong>/s', function($matches) use ($anuncio, $anuncio_geral, &$count) {
                            $count++;

                            if ($count == 2) {
                                return $anuncio . '<p style="text-align:justify"><strong>' . $matches[1] . '</strong>';
                            }

                            if ($count == 3 || $count == 4) {
                                return $anuncio_geral . '<p style="text-align:justify"><strong>' . $matches[1] . '</strong>';
                            }
                            return $matches[0];
                        }, $artigo["texto"]);

                        echo $html;

                        ?>
                </div>
            </div>
        </div>
    </article>

    <?php $useragent=$_SERVER['HTTP_USER_AGENT'];

 if(!preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))) { ?>

        <div style="
        width: 500px;
        position: relative;
        left: 32%;">
            <script type="text/javascript">
                atOptions = {
                    'key' : '53c8c6c855497a84da8a56e922c286af',
                    'format' : 'iframe',
                    'height' : 90,
                    'width' : 728,
                    'params' : {}
                };
                document.write('<scr' + 'ipt type="text/javascript" src="http' + (location.protocol === 'https:' ? 's' : '') + '://www.profitabledisplaynetwork.com/53c8c6c855497a84da8a56e922c286af/invoke.js"></scr' + 'ipt>');
            </script>
        </div>
    

    <?php } ?>

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
    <script src="js/site.js"></script>
    <script src="js/scripts.js"></script>
    <script src="https://use.fontawesome.com/releases/v5.15.3/js/all.js" crossorigin="anonymous"></script>
</body>

</html>