<?php
include_once("fachada.php");

$lstCodRespondentes = isset($_POST["lstRespondentes"]) ? $_POST["lstRespondentes"] : FALSE; // array com os códigos de respondentes selecionados
$codQuestionario = isset($_POST["codQuestionario"]) ? addslashes(trim($_POST["codQuestionario"])) : FALSE;

$questionario = null;
try {
 $questionario = $factory->getQuestionarioQuestaoDao()->buscarPorId($codQuestionario);
} catch (\Throwable $th) {
 $response = array(
  "status" => "error",
  "message" => $th->getMessage(),
  "stackTrace" => $th->getTraceAsString()
 );
 echo json_encode($response);
 exit;
}

$respondentes = array();
try {
 foreach ($lstCodRespondentes as $codRespondente) {
  $respondentes[] = $factory->getUsuarioDao()->buscarPorId($codRespondente);
 }
} catch (\Throwable $th) {
 $response = array(
  "status" => "error",
  "message" => $th->getMessage(),
  "stackTrace" => $th->getTraceAsString()
 );
 echo json_encode($response);
 exit;
}

$dtaOferta = (new \DateTime())->format('d-m-Y H:i:s');

try {
 foreach ($respondentes as $respondente) {
  $factory->getOfertaDao()->inserir(new Oferta(null, $dtaOferta, $questionario, $respondente));
 }
} catch (\Throwable $th) {
 $response = array(
  "status" => "error",
  "message" => $th->getMessage(),
  "stackTrace" => $th->getTraceAsString()
 );
 echo json_encode($response);
 exit;
}

$response = array(
 "status" => "success",
 "message" => "Oferta salva com sucesso"
);
echo json_encode($response);
?>