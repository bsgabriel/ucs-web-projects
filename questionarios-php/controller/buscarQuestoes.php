<?php
include_once("fachada.php");
$questoes = $factory->getQuestaoDao()->buscarTodos();

$retQuestoes = array();
foreach ($questoes as $questao) {
 $alternativas = array();
 if (!is_null($questao->getAlternativas()) && !empty($questao->getAlternativas())) {
  foreach ($questao->getAlternativas() as $alternativa) {
   $alternativas[] = array(
    "idAlternativa" => $alternativa->getId(),
    "descricao" => $alternativa->getDescricao(),
    "correta" => $alternativa->getCorreta()
   );
  }

 }
 $retQuestoes[] = array(
  "idQuestao" => $questao->getId(),
  "descricao" => $questao->getDescricao(),
  "tipo" => $questao->getTipo(),
  "alternativas" => $alternativas
 );
}
echo json_encode($retQuestoes);
?>