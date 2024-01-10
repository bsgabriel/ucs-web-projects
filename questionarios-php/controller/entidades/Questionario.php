<?php
class Questionario implements JsonObject
{
 private $id;
 private $nome;
 private $descricao;
 private $dataCriacao;
 private $notaAprovacao;
 private $elaborador;
 private $questoes;

 public function __construct($id, $nome, $descricao, $dataCriacao, $notaAprovacao, $elaborador, $questoes)
 {
  $this->id = $id;
  $this->nome = $nome;
  $this->descricao = $descricao;
  $this->dataCriacao = $dataCriacao;
  $this->notaAprovacao = $notaAprovacao;
  $this->elaborador = $elaborador; //Recebe um objeto da classe Elaborador.php
  $this->questoes = $questoes; //Recebe um vetor com objetos da classe Questao.php
 }

 public function getId()
 {
  return $this->id;
 }

 public function setId($id)
 {
  $this->id = $id;

  return $this;
 }

 public function getNome()
 {
  return $this->nome;
 }

 public function setNome($nome)
 {
  $this->nome = $nome;

  return $this;
 }

 public function getDescricao()
 {
  return $this->descricao;
 }

 public function setDescricao($descricao)
 {
  $this->descricao = $descricao;

  return $this;
 }

 public function getDataCriacao()
 {
  return $this->dataCriacao;
 }

 public function setDataCriacao($dataCriacao)
 {
  $this->dataCriacao = $dataCriacao;

  return $this;
 }

 public function getNotaAprovacao()
 {
  return $this->notaAprovacao;
 }

 public function setNotaAprovacao($notaAprovacao)
 {
  $this->notaAprovacao = $notaAprovacao;

  return $this;
 }

 public function getElaborador()
 {
  return $this->elaborador;
 }

 public function setElaborador($elaborador)
 {
  $this->elaborador = $elaborador;

  return $this;
 }

 public function getQuestoes()
 {
  return $this->questoes;
 }

 public function setQuestoes($questoes)
 {
  $this->questoes = $questoes;

  return $this;
 }

 public function fromJson($json)
 {

 }

 public function toJson(): array
 {
  $arrQuestoes = [];
  foreach ($this->getQuestoes() as $questao) {
   $arrQuestoes[] = $questao->toJson();
  }

  return array(
   "id" => $this->getId(),
   "nome" => $this->getNome(),
   "descricao" => $this->getDescricao(),
   "dataCriacao" => $this->getDataCriacao(),
   "elaborador" => $this->getElaborador()->toJson(),
   "questoes" => $arrQuestoes
  );
 }
}
?>