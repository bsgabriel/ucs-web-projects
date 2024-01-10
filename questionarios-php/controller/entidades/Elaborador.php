<?php

include_once("Usuario.php");

class Elaborador extends Usuario
{
  private $instituicao;

  public function __construct($id, $login, $senha, $nome, $email, $instituicao)
  {
    parent::__construct($id, $login, $senha, $nome, $email, 'E');
    $this->instituicao = $instituicao;
  }

  public function getInstituicao()
  {
    return $this->instituicao;
  }

  public function setInstituicao($instituicao)
  {
    $this->instituicao = $instituicao;
  }

  public function fromJson($json)
  {
    // TODO: implementar
  }

  public function toJson(): array
  {
    return array(
      "id" => $this->getId(),
      "tipo" => $this->getTipo(),
      "nome" => $this->getNome(),
      "login" => $this->getLogin(),
      "senha" => $this->getSenha(),
      "email" => $this->getEmail(),
      "campoExtra" => $this->getInstituicao()
    );
  }
}

?>