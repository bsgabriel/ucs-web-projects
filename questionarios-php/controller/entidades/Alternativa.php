<?php
class Alternativa implements JsonObject
{
  private $id;
  private $descricao;
  private $correta;
  private $idQuestao;

  public function __construct($id, $descricao, $correta, $idQuestao)
  {
    $this->id = $id;
    $this->descricao = $descricao;
    $this->correta = $correta;
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

  public function getDescricao()
  {
    return $this->descricao;
  }

  public function setDescricao($descricao)
  {
    $this->descricao = $descricao;

    return $this;
  }

  public function getCorreta()
  {
    return $this->correta;
  }

  public function setCorreta($correta)
  {
    $this->correta = $correta;

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
      "idQuestao" => $this->getIdQuestao(),
      "descricao" => $this->getDescricao(),
      "correta" => $this->getCorreta(),
    );
  }
}

?>