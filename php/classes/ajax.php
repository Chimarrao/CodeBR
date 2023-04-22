<?php

class ajax extends sistema {    
    /**
     * Construtor
     *
     * @return void
     */
    public function __construct() {}
    
    /**
     * Registra um novo contato no banco de dados
     *
     * @return void
     */
    public function registrarContato() {
        /**
         * Função não usada por enquanto
         */
    }
    
    /**
     * Registra um novo acesso no banco de dados
     *
     * @return void
     */
    public function registrarAcesso() {
        $ip                         = $this->getIP();
        $excluido                   = 0;
        $url_atual                  = $this->getURLAtual();
        $url_anterior               = $this->getURLAnterior();

        $pdo                        = $this->getConection();
        $query                      = $pdo->prepare("INSERT INTO `acessos` (url_anterior, url_acessada, ip_usuario, excluido) VALUES (?, ?, ?, ?)");
        $query->bindParam(1, $url_anterior, PDO::PARAM_STR);
        $query->bindParam(2, $url_atual, PDO::PARAM_STR);
        $query->bindParam(3, $ip, PDO::PARAM_STR);
        $query->bindParam(4, $excluido, PDO::PARAM_INT);
        $query->execute();
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

if (isset($_POST["ajax"])) {
    extract($_POST);
    
    if ($ajax == "registrarAcesso") {
        $ajax                       = new ajax();
        $ajax->registrarAcesso();
    }

    die();
}