<?php
interface QuestionarioDAO
{
 public function inserir($questionario);
 public function remover($questionario);
 public function alterar($questionario);
 public function buscarTodos();
 public function buscarPorId($id);
 public function buscarPorNome($nome);
 public function buscarPorElaborador($elaborador);
 public function buscarTodosOffset(int $start, int $limit);
}
?>