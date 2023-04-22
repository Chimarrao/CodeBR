<?php

class template {
    /**
     * @var string $caminho         Caminho para o arquivo
     */
    private string $caminho         = __DIR__ . "/../../www/view/";
    
    /**
     * Construtor
     *
     * @param  string $caminho      Pasta de onde a view será chamada
     * @return void
     */
    public function __construct(string $caminho = "") {
        echo                        "<base href='" . SITE_URL . "www/view/'>";

        if ($caminho != "") {
            $caminho[-1] != "/" ? $this->caminho  = $caminho . "/" : $this->caminho = $caminho;
        }
    }
    
    /**
     * Carrega o arquivo html
     *
     * @param  string $arquivo      Nome do arquivo
     * @param  array $parametros    Parâmetros da página
     * @return void|false           False em caso de erro
     */
    public function carregar(string $arquivo, array $parametros = array()) {
        $arquivo                    = $this->caminho . $arquivo;
        if (!is_readable($arquivo)) {
            return False;
        }
        
        if (!is_dir($this->caminho)) {
            return False;
        }

        extract($parametros);
        ob_start();
        include_once                $arquivo;
        echo ob_get_clean();
    }
}