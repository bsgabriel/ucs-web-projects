<?php
class QuestionarioQuestao implements JsonObject
{
 private $id;
 private $pontos;
 private $ordem;
 private $idQuestionario;
 private $idQuestao;

 public function __construct($id, $pontos, $ordem, $idQuestionario, $idQuestao)
 {
  $this->id = $id;
  $this->pontos = $pontos;
  $this->ordem = $ordem;
  $this->idQuestionario = $idQuestionario;
  $this->idQuestao = $idQuestao;
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

 public function getPontos()
 {
  return $this->pontos;
 }

 public function setPontos($pontos)
 {
  $this->pontos = $pontos;

  return $this;
 }

 public function getOrdem()
 {
  return $this->ordem;
 }

 public function setOrdem($ordem)
 {
  $this->ordem = $ordem;

  return $this;
 }

 public function getIdQuestionario()
 {
  return $this->idQuestionario;
 }

 public function setIdQuestionario($idQuestionario)
 {
  $this->idQuestionario = $idQuestionario;

  return $this;
 }

 public function getIdQuestao()
 {
  return $this->idQuestao;
 }

 public function setIdQuestao($idQuestao)
 {
  $this->idQuestao = $idQuestao;

  return $this;
 }
 public function fromJson($json)
 {

 }

 public function toJson(): array
 {
  return array(
   "id" => $this->getId(),
   "pontos" => $this->getPontos(),
   "ordem" => $this->getOrdem(),
   "idQuestao" => $this->getIdQuestao(),
   "idQuestionario" => $this->getIdQuestionario()
  );
 }
}

?>