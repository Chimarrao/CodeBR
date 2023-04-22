<?php

trait urls {    
    /**
     * Retorna a URL atual completa em PHP
     *
     * @return string               URL atual
     */
    protected function getURLAtual() {
        return "http" . (isset($_SERVER["HTTPS"]) ? "s" : "") . "://" . "{$_SERVER["HTTP_HOST"]}/{$_SERVER["REQUEST_URI"]}";
    }
    
    /**
     * Retorna a URL anterior
     *
     * @return string               URL anterior
     */
    protected function getURLAnterior() {
        return $_SERVER["HTTP_REFERER"];
    }
}

trait ip {    
    /**
     * Retorna o IP atual do usuário
     *
     * @return string               IP do usuário
     */
    protected function getIP() {
        return $_SERVER["REMOTE_ADDR"];
    }
}