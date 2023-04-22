<?php
require_once __DIR__ . "/../../php/vendor/autoload.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class blogController extends sistema {
    /**
     * @var object $template        Classe de template
     */
    private static object $template;

    /**
     * Construtor
     * 
     * @return void
     */
    public function __construct() {
        self::$template             = new template();
    }
        
    /**
     * Carrega o index
     *
     * @return void
     */
    public function index() {
        $artigos                    = $this->getArtigos();

        $parametros = array (
            "artigos"               => $artigos,
        );

        self::$template->carregar(SITE_INDEX, $parametros);
    }
    
    /**
     * Carrega o artigo
     *
     * @return void
     */
    public function artigo() {
        $artigo                     = $this->getArtigo();

        /**
         * Gambiarra (não façam isso em casa hahaha)
         * A correção será feita na reescrita das rotas
         */
        if (!$artigo) {
            echo "<script>window.location="https://codebr.net"</script>";
            die();
        }

        $parametros = array (
            "artigo"                => $artigo,
        );

        self::$template->carregar(SITE_ARTIGO, $parametros);
    }

    /**
     * Carrega a página de sobre
     *
     * @return void
     */
    public function sobre() {
        $artigos                    = array();

        $parametros = array (
            "artigos"               => $artigos,
        );

        self::$template->carregar(SITE_SOBRE, $parametros);
    }

    /**
     * Carrega a página de política de privacidade
     *
     * @return void
     */
    public function politicaDePrivacidade() {
        self::$template->carregar(SITE_POLITICA_PRIVACIDADE, []);
    }

    /**
     * Carrega a página de contato
     *
     * @return void
     */
    public function contato() {
        $parametros                 = array ();
        
        if (isset($_POST)) {
            $mensagem               = "";
            
            foreach ($_POST as $chave => $valor) {
                $mensagem           .= "$chave: $valor <br>";
            }
            
            $mail                       = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host       = "smtp.titan.email";
                $mail->SMTPAuth   = true;
                $mail->Username   = "site@codebr.net";
                $mail->Password   = SENHA_EMAIL;
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port       = 465;
            
                $mail->setFrom("site@codebr.net", "CodeBR");
                $mail->addAddress(EMAIL_PESSOAL, "Lucas Reolon");
            
                $mail->isHTML(true);
                $mail->Subject = "Mensagem através do site";
                $mail->Body    = "$mensagem";
            
                $mail->send();
            } catch (Exception $e) { }
        }

        self::$template->carregar(SITE_CONTATO, $parametros);
    }
    
    /**
     * Retorna um artigo pela URl
     *
     * @return array|false          Artigo ou False em caso de erro
     */
    private function getArtigo() {
        $url                        = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        $url                        = explode("artigo/", $url);
        $url                        = $url[1];
        $url                        = explode("/", $url);
        $url                        = $url[0];

        $pdo                        = $this->getConection();
        $query                      = $pdo->prepare("SELECT * FROM `artigos` WHERE `url` = "$url" ");
        $query->execute();

        $artigo                     = $query->fetch();

        if (!$artigo) {
            return False;
        }

        return $artigo;
    }

    /**
     * Retorna todos os artigos do sistema
     *
     * @return array|false          Artigos ou False em caso de erro
     */
    private function getArtigos() {
        $pdo                        = $this->getConection();
        $query                      = $pdo->prepare("SELECT * FROM `artigos` WHERE `liberado` = "1" ORDER BY `id_artigo` DESC ");
        $query->execute();

        $artigos                    = $query->fetchAll();

        if (!$artigos) {
            return False;
        }

        return $artigos;
    }

    /**
	 * Cria e retorna uma conexão PDO
	 *
	 * @return PDO
	 */
    private function getConection() {
        $pdo					    = new PDO(DB_DNS, DB_USER, DB_PASS);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
		return $pdo;
    }
}