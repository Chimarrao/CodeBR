<?php
date_default_timezone_set("America/Sao_Paulo");
setlocale(LC_ALL, "pt_BR");
require_once __DIR__ . "/php/sistema.php";
$rotas = new rotas();

/**
 * Index
 */
$rotas->addRota("", "blogController", "index");
$rotas->addRota("index", "blogController", "index");
$rotas->addRota("index.php", "blogController", "index");
$rotas->addRota("index.html", "blogController", "index");

/**
 * Artigo
 */
$rotas->addRota("artigo", "blogController", "artigo");
$rotas->addRota("artigo.php/", "blogController", "artigo");
$rotas->addRota("artigo.html", "blogController", "artigo");

/**
 * Página de sobre
 */
$rotas->addRota("sobre", "blogController", "sobre");
$rotas->addRota("sobre.php/", "blogController", "sobre");
$rotas->addRota("sobre.html", "blogController", "sobre");

/**
 * Página de contato
 */
$rotas->addRota("contato", "blogController", "contato");
$rotas->addRota("contato.php/", "blogController", "contato");
$rotas->addRota("contato.html", "blogController", "contato");

/**
 * Administrativo
 */
$rotas->addRota("politica-de-privacidade", "blogController", "politicaDePrivacidade");
$rotas->addRota("politica-de-privacidade.php/", "blogController", "politicaDePrivacidade");
$rotas->addRota("politica-de-privacidade.html", "blogController", "politicaDePrivacidade");

$rotas->executar();
