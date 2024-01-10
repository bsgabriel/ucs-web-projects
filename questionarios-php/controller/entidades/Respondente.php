<?php

include_once("Usuario.php");
class Respondente extends Usuario
{
  private $telefone;

  public function __construct($id, $login, $senha, $nome, $email, $telefone)
  {
    parent::__construct($id, $login, $senha, $nome, $email, 'R');
    $this->telefone = $telefone;
  }

  public function getTelefone()
  {
    return $this->telefone;
  }

  public function setTelefone($telefone)
  {
    $this->telefone = $telefone;
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
      "instituicao" => null,
      "telefone" => $this->getTelefone()
    );
  }
}

?>