<?php

const ERRO_USUARIO_INVALIDO = "Usuário ou senha inválidos";
const ERRO_CAMPOS_NAO_INFORMADOS = "Usuário ou senha não informados";

include_once("fachada.php");
include_once("GlobalKeys.php");
session_start();

$login = isset($_POST["login"]) ? addslashes(trim($_POST["login"])) : FALSE;
$senha = isset($_POST["senha"]) ? md5(trim($_POST["senha"])) : FALSE;

if (!$login || !$senha) {
 $response = array(
  "status" => "error",
  "message" => ERRO_CAMPOS_NAO_INFORMADOS
 );
 echo json_encode($response);
 exit;
}

// busca o usuário
$usuario = $factory->getUsuarioDao()->buscarPorLogin($login);

if (!$usuario) {
 $response = array(
  "status" => "error",
  "message" => ERRO_USUARIO_INVALIDO
 );
 echo json_encode($response);
 exit;
}

if (strcmp($senha, $usuario->getSenha())) {
 $response = array(
  "status" => "error",
  "message" => ERRO_USUARIO_INVALIDO
 );
 echo json_encode($response);
 exit;
}

// TODO procurar outra alternativa ao invés de armazenar o cookie com tipo de usuário
setcookie(GlobalKeys::TIPO_USUARIO_AUTENTICADO, $usuario->getTipo(), null, "/");
setcookie(GlobalKeys::ID_USUARIO_AUTENTICADO, $usuario->getId(), null, "/");

$_SESSION[GlobalKeys::ID_USUARIO_AUTENTICADO] = $usuario->getId();
$_SESSION[GlobalKeys::TIPO_USUARIO_AUTENTICADO] = $usuario->getTipo();

$response = array(
 "status" => "success",
 "message" => ""
);

echo json_encode($response);

?>