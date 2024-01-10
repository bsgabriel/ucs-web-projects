<?php
include_once "fachada.php";

$nome = isset($_POST["nome"]) ? addslashes(trim($_POST["nome"])) : FALSE;
$descricao = isset($_POST["descricao"]) ? addslashes(trim($_POST["descricao"])) : FALSE;
$notaAprovacao = isset($_POST["notaAprovacao"]) ? addslashes(trim($_POST["notaAprovacao"])) : FALSE;
$codElaborador = isset($_POST["codElaborador"]) ? addslashes(trim($_POST["codElaborador"])) : FALSE;
$questoes = isset($_POST["questoes"]) ? $_POST["questoes"] : FALSE;

$elaborador = null;
try {
 $elaborador = $factory->getUsuarioDao()->buscarPorId($codElaborador);
} catch (\Throwable $th) {
 $response = array(
  "status" => "error",
  "message" => $th->getMessage(),
  "stackTrace" => $th->getTraceAsString()
 );
 echo json_encode($response);
 exit;
}

$codQuestionario = null;
try {
 // salva o questionário retornando o ID do mesmo
 $codQuestionario = $factory->getQuestionarioDao()->inserir(new Questionario(null, $nome, $descricao, (new \DateTime())->format('d-m-Y H:i:s'), $notaAprovacao, $elaborador, null));
} catch (\Throwable $th) {
 $response = array(
  "status" => "error",
  "message" => $th->getMessage(),
  "stackTrace" => $th->getTraceAsString()
 );
 echo json_encode($response);
 exit;
}

$ordem = 0; // TODO: ver sobre ordem da questão, pois a princípio elas seriam em ordem aleatórias...

try {
 foreach ($questoes as $questao) {
  $factory->getQuestionarioQuestaoDao()->inserir(new QuestionarioQuestao(null, $questao["valorQuestao"], $ordem, $codQuestionario, $questao["idQuestao"]));
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
 "message" => "Questionário cadastrado com sucesso",
);
echo json_encode($response);

exit;
?>