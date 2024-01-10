<?php
include_once("fachada.php");
$codUsuario = $_GET["codUsuario"];

$usuario = null;
try {
 $usuario = $factory->getUsuarioDao()->buscarPorId($codUsuario);
} catch (\Throwable $th) {
 $response = array(
  "status" => "error",
  "message" => $th->getMessage(),
  "stack" => $th->getTraceAsString()
 );
 echo json_encode($response);
 exit;
}

if (is_null($usuario) || is_null($usuario->getId())) {
 $response = array(
  "status" => "notFound",
  "message" => "Usuário não encontrado"
 );
 echo json_encode($response);
 exit;
}

$campoExtra = "";

if (strcmp($usuario->getTipo(), "E") == 0) {
 $campoExtra = $usuario->getInstituicao();
} else if (strcmp($usuario->getTipo(), "R") == 0) {
 $campoExtra = $usuario->getTelefone();
}


$response = array(
 "status" => "success",
 "message" => "Usuário encontrado",
 "id" => $usuario->getId(),
 "tipo" => $usuario->getTipo(),
 "nome" => $usuario->getNome(),
 "login" => $usuario->getLogin(),
 "senha" => $usuario->getSenha(),
 "email" => $usuario->getEmail(),
 "campoExtra" => $campoExtra
);
echo json_encode($response);
?>