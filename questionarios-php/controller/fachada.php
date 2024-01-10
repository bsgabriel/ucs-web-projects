<?php
error_reporting(E_ERROR | E_PARSE);
include_once(__DIR__ . '/entidades/Elaborador.php');
include_once(__DIR__ . '/entidades/Respondente.php');
include_once(__DIR__ . '/entidades/Administrador.php');
include_once(__DIR__ . '/entidades/Questao.php');
include_once(__DIR__ . '/entidades/Alternativa.php');
include_once(__DIR__ . '/entidades/Questionario.php');
include_once(__DIR__ . '/entidades/QuestionarioQuestao.php');
include_once(__DIR__ . '/entidades/Oferta.php');
include_once(__DIR__ . '/dao/UsuarioDAO.php');
include_once(__DIR__ . '/dao/QuestionarioDAO.php');
include_once(__DIR__ . '/dao/QuestionarioQuestaoDAO.php');
include_once(__DIR__ . '/dao/DaoFactory.php');
include_once(__DIR__ . '/dao/OfertaDAO.php');
include_once(__DIR__ . '/dao/postgres/PostgresDaoFactory.php');
$factory = new PostgresDaoFactory();
?>