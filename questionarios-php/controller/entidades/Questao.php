<?php
class Questao implements JsonObject
{
  private $id;
  private $descricao;
  private $tipo;
  private $imagem;
  private $alternativas;

  public function __construct($id, $descricao, $tipo, $imagem, $alternativas)
  {
    $this->id = $id;
    $this->descricao = $descricao;
    $this->tipo = $tipo; //'D' = discursiva | 'M' = múltipla escolha | 'V' = verdadeiro ou falso
    $this->imagem = $imagem;
    $this->alternativas = $alternativas; //Vetor com objetos da classe "Alternativa.php"
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

  public function getTipo()
  {
    return $this->tipo;
  }

  public function setTipo($tipo)
  {
    $this->tipo = $tipo;

    return $this;
  }

  public function getImagem()
  {
    return $this->imagem;
  }

  public function setImagem($imagem)
  {
    $this->imagem = $imagem;

    return $this;
  }

  public function getAlternativas()
  {
    return $this->alternativas;
  }

  public function setAlternativas($alternativas)
  {
    $this->alternativas = $alternativas;

    return $this;
  }

  public function fromJson($json)
  {

  }

  public function toJson(): array
  {
    $arrAlternativas = [];
    foreach ($this->getAlternativas() as $alternativa) {
      $arrAlternativas[] = $alternativa->toJson();
    }
    return array(
      "id" => $this->getId(),
      "tipo" => $this->getTipo(),
      "descricao" => $this->getDescricao(),
      "imagem" => $this->getImagem(),
      "alternativas" => $arrAlternativas
    );
  }

}
?>