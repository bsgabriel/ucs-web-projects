<?php
include_once("fachada.php");
$idQuestionario = isset($_GET["idQuestionario"]) ? addslashes(trim($_GET["idQuestionario"])) : FALSE;

if (!$idQuestionario) {
 $response = array(
  "status" => "error",
  "message" => "ID de questionário não informado",
 );
 echo json_encode($response);
 exit;
}

$questionario = null;
try {
 $questionario = $factory->getQuestionarioDao()->buscarPorId($idQuestionario);
} catch (\Throwable $th) {
 $response = array(
  "status" => "error",
  "message" => "Não foi possível buscar questionário",
  "errorMessage" => $th->getMessage(),
  "stackTrace" => $th->getTraceAsString()
 );
 echo json_encode($response);
 exit;
}

if (is_null($questionario)) {
 $response = array(
  "status" => "error",
  "message" => "Questionário não encontrado",
 );
 echo json_encode($response);
 exit;
}

echo json_encode($questionario->toJson())

 ?>