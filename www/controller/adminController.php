<?php

class adminController extends sistema {
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
        $acessos                    = $this->getAcessos();

        $parametros = array (
            "acessos"               => $acessos,
        );

        self::$template->carregar(ADMIN_INDEX, $parametros);
    }

    /**
     * Carrega o login
     *
     * @return void
     */
    public function login() {
        $parametros                 = array();
        
        if ($this->post("login_admin")) {
            $login                  = $this->loginAdmin();

            if ($login) {
                header("Location: " . ADMIN_INDEX);
                die();
            } else {
                $parametros = array(
                    "erro_login"    => 1
                );
            }
        }
        
        self::$template->carregar(ADMIN_LOGIN, $parametros);
    }
    
    /**
     * Realiza o login no painel administrativo
     *
     * @return bool                 True para logado, False caso contrário
     */
    private function loginAdmin() {
        extract($_POST);
            
        if (!$email || !$senha) {
            return False;
        }
        
        $email                      = $this->string($email);
        $senha                      = $this->string($senha);

        $pdo                        = $this->getConection();
        $query                      = $pdo->prepare("SELECT * FROM `usuarios` WHERE `email` = '$email' ");
        $query->execute();
        $usuario                    = $query->fetch();

        if (!$usuario) {
            return False;
        }

        if (password_verify($senha, $usuario["senha"])) {
            $_SESSION[ID_USUARIO]   = $usuario["id_usuario"];
            return True;
        }

        return False;
    }
    
    /**
     * Retorna todos os acessos do site
     *
     * @return array|false          Array com os acessos ou False em caso de erro
     */
    private function getAcessos() {
        $pdo                        = $this->getConection();
        $query                      = $pdo->prepare("SELECT * FROM `acessos` ORDER BY `id_acesso` DESC");
        $query->execute();
        return $query->fetchAll();
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