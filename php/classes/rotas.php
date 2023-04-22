<?php

class rotas {
    /**
     * @var array $rotas            Array com as rotas do sistema
     */
    private array $rotas;

    /**
     * Construtor
     * 
     * @return void
     */
    public function __construct() {}
    
    /** 
     * Adiciona uma nova rota
     * 
     * @param  string $pagina       Nome da página
     * @param  string $controller   Controller ao qual a página será direcionada
     * @param  string $metodo       Método do controller que será chamando
     * @param  boolean $usuario     Se a página está protegiada por login de usuário
     * @param  boolean $usuario     Se a página está protegiada por login de cliente
     * @return void
     */
    public function addRota(string $pagina, string $controller, string $metodo, bool $usuario = False, bool $cliente = False) {
        $this->rotas[$pagina] = array (
            "metodo"                => $metodo,
            "usuario"               => $usuario,
            "cliente"               => $cliente,
            "controller"            => $controller,
        );
    }

    /**
     * Executa o sistema de rotas
     * 
     * @return void
     */
    public function executar() {        
        $pagina                     = $this->getPagina();
        $pagina_valida              = False;

        foreach ($this->rotas as $rota => $parametros) {
            if ($pagina == $rota) {
                $pagina             = $parametros;
                $pagina_valida      = True;
            }
        }

        if ($pagina_valida) {
            $cliente                = $pagina["cliente"];
            $usuario                = $pagina["usuario"];

            if (!$usuario && !$cliente || ($usuario && $this->usuarioLogado()) || ($cliente && $this->clienteLogado())) {
                $controller         = $pagina["controller"];
                $funcao             = $pagina["metodo"];
                
                require             __DIR__ . "/../../www/controller/" . $controller . ".php";
                $controller         = new $controller;
                echo                call_user_func(array($controller, $funcao));
            } else {
                if ($cliente) {
                    header("Location: " . SITE_INDEX);
                } elseif ($usuario) {
                    header("Location: " . ADMIN_LOGIN);
                } else {
                    header("Location: " . SITE_INDEX);
                }
            }
        } else {
            /**
             * @todo
             * Carregar página não encontrada
             */
            header("Location: " . SITE_URL);
        }
    }
    
    /**
     * Retorna a página atual sem os parâmetros
     * 
     * @return string $pagina       Página atual
     */
    private function getPagina() {
        $pagina                     = str_replace(SITE_URL, "", $_SERVER["REQUEST_URI"]);
        $pagina                     = str_replace(SITE_SUBPASTA, "", $pagina);
        
        if ($pagina[0] == "/" || $pagina[0] == "\\") {
            $pagina                 = substr($pagina, 1);
        }
        
        $pagina                     = explode("/", $pagina)[0];
        
        return $pagina;
    }

    /**
     * Verifica se o cliente está logado
     *
     * @return boolean $logado      True se está logado, False se não está
     */
    private function clienteLogado() {
        /**
         * Essa função será ativada quando houver login de cliente no site
         */
        return False;
    }
    
    /**
     * Verifica se o usuário está logado
     *
     * @return boolean $logado      True se está logado, False se não está
     */
    private function usuarioLogado() {
        /**
         * Essa função será ativada quando houver painel administrativo
         */
        return False;
    }
}