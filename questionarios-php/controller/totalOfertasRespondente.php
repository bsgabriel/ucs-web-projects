<?php
include_once("fachada.php");
$idUsuario = isset($_GET["idUsuario"]) ? addslashes(trim($_GET["idUsuario"])) : FALSE;

// verifica se id de usuário foi informado
if (!$idUsuario) {
 $response = array(
  "status" => "error",
  "message" => "ID de usuário não informado",
 );
 echo json_encode($response);
 exit;
}

// busca usuário
$usuario = null;
try {
 $usuario = $factory->getUsuarioDao()->buscarPorId($idUsuario);
} catch (\Throwable $th) {
 $response = array(
  "status" => "error",
  "message" => "Não foi possível buscar informações do usuário",
  "errorMessage" => $th->getMessage(),
  "stackTrace" => $th->getTraceAsString()
 );
 echo json_encode($response);
 exit;
}

// verifica se encontrou usuário
if (is_null($usuario)) {
 $response = array(
  "status" => "error",
  "message" => "Nenhum usuário encontrado para o ID informado",
 );
 echo json_encode($response);
 exit;
}

// verifica se é respondente
if (!($usuario instanceof Respondente)) {
 $response = array(
  "status" => "error",
  "message" => "Tipo de usuário inválido",
 );
 echo json_encode($response);
 exit;
}

// busca as ofertas para o respondente
$ofertas = null;
try {
 $ofertas = $factory->getOfertaDao()->buscarOfertasPorRespondente($usuario);
} catch (\Throwable $th) {
 $response = array(
  "status" => "error",
  "message" => "Não foi possível buscar ofertas",
  "errorMessage" => $th->getMessage(),
  "stackTrace" => $th->getTraceAsString()
 );
 echo json_encode($response);
 exit;
}

$response = array(
 "total" => sizeof($ofertas),
);
echo json_encode($response);

?>