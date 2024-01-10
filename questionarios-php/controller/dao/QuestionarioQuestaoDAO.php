<?php
interface QuestionarioQuestaoDAO
{
 public function inserir($questionarioQuestao);
 public function alterar($questionarioQuestao);
 public function remover($questionarioQuestao);
 public function removerTodasQuestoesDoQuestionario($id_questionario); //Para remover todas questões do questionário
 public function buscarPorId($id);
 public function buscarQuestoesQuestionario($id_questionario);
}
?>