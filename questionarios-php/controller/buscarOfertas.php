<?php
include_once "fachada.php";
$idUsuario = isset($_GET["idUsuario"]) ? addslashes(trim($_GET["idUsuario"])) : FALSE;
$start = isset($_GET["start"]) ? addslashes(trim($_GET["start"])) : FALSE;
$limit = isset($_GET["limit"]) ? addslashes(trim($_GET["limit"])) : FALSE;

if (!$idUsuario) {
 $response = array(
  "status" => "error",
  "message" => "ID de usuário não informado",
 );
 echo json_encode($response);
 exit;
}

/* BUSCA DE USUÁRIO */
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

if (is_null($usuario)) {
 $response = array(
  "status" => "error",
  "message" => "Nenhum usuário encontrado para o ID informado",
 );
 echo json_encode($response);
 exit;
}

/* BUSCA DE OFERTAS */

$ofertas = null;
try {
 if (!$start && !$limit)
  $ofertas = $factory->getOfertaDao()->buscarOfertasPorRespondente($usuario);
 else
  $ofertas = $factory->getOfertaDao()->buscarOfertasPorRespondenteOffset($usuario, $start, $limit);
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

if (is_null($ofertas) || empty($ofertas)) {
 $response = array(
  "status" => "error",
  "message" => "Nenhuma oferta encontrada",
 );
 echo json_encode($response);
 exit;
}

$arrOfertas = array();
foreach ($ofertas as $oferta) {
 $arrOfertas[] = $oferta->toJSon();
}
echo json_encode($arrOfertas);


?>