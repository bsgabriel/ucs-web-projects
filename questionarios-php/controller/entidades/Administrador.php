<?php

include_once("Usuario.php");
class Administrador extends Usuario
{
  public function __construct($id, $login, $senha, $nome, $email)
  {
    parent::__construct($id, $login, $senha, $nome, $email, 'A');
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
      "campoExtra" => ""
    );
  }
}

?>