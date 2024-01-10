<?php
interface QuestaoDAO
{
  public function inserir($questao);
  public function remover($questao);
  public function alterar($questao);
  public function buscarTodos();
  public function buscarPorId($id);
}
?>