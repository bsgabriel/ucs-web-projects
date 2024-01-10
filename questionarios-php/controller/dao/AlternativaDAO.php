<?php
interface AlternativaDAO
{
  public function inserir($alternativa);
  public function remover($alternativa);
  public function alterar($alternativa);
  public function buscarTodos();
  public function buscarPorId($id);
  public function buscarPorQuestao($id_questao);
  public function removerPorQuestao($id_questao);
}
?>