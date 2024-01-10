<?php
include_once("fachada.php");
$start = isset($_GET["start"]) ? addslashes(trim($_GET["start"])) : FALSE;
$limit = isset($_GET["limit"]) ? addslashes(trim($_GET["limit"])) : FALSE;

$questionarios = null;
if (!$start && !$limit)
 $questionarios = $factory->getQuestionarioDao()->buscarTodos();
else
 $questionarios = $factory->getQuestionarioDao()->buscarTodosOffset($start, $limit);

$list = array();
foreach ($questionarios as $questionario) {
 $elaborador = array(
  "id" => $questionario->getElaborador()->getId(),
  "login" => $questionario->getElaborador()->getLogin(),
  "nome" => $questionario->getElaborador()->getNome(),
  "email" => $questionario->getElaborador()->getEmail(),
  "tipo" => $questionario->getElaborador()->getTipo(),
 );

 $questoes = array();

 // busca as questões do questionário
 foreach ($questionario->getQuestoes() as $questao) {
  $alternativas = array();

  // busca as alternativas de cada questão e monta um array
  foreach ($questao->getAlternativas() as $alternativa) {
   $alternativas[] = array(
    "id" => $alternativa->getId(),
    "descricao" => $alternativa->getDescricao(),
    "correta" => $alternativa->getCorreta(),
    "idQuestao" => $alternativa->getIdQuestao()
   );
  }

  // monta o array de questões
  $questoes[] = array(
   "id" => $questao->getId(),
   "tipo" => $questao->getTipo(),
   "descricao" => $questao->getDescricao(),
   "imagem" => $questao->getImagem(),
   "alternativas" => $alternativas
  );
 }

 $list[] = array(
  "id" => $questionario->getId(),
  "nome" => $questionario->getNome(),
  "descricao" => $questionario->getDescricao(),
  "notaAprovacao" => $questionario->getNotaAprovacao(),
  "dataCriacao" => $questionario->getDataCriacao(),
  "elaborador" => $elaborador,
  "questoes" => $questoes
 );
}
echo json_encode($list);
?>