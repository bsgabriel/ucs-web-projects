<?php
interface OfertaDAO
{
  public function inserir($oferta);
  public function alterar($oferta);
  public function remover($oferta);
  public function removerOfertasQuestionario($id_questionario); //Remove todas ofertas de um questionário
  public function removerOfertasRespondente($id_respondente); //Remove todas ofertas de um respondente
  public function buscarPorId($id);
  public function buscarOfertasPorQuestionario($questionario); //Busca todas ofertas de um questionário
  public function buscarOfertasPorRespondente(Respondente $respondente); //Busca todas ofertas de um respondente
  public function buscarOfertasPorRespondenteOffset(Respondente $respondente, int $start, int $limit); //Busca todas ofertas de um respondente
}