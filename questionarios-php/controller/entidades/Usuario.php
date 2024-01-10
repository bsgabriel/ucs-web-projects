<?php
include_once("JsonObject.php");
abstract class Usuario implements JsonObject
{

  protected $id;
  protected $login;
  protected $senha;
  protected $nome;
  protected $email;
  protected $tipo;

  protected function __construct($id, $login, $senha, $nome, $email, $tipo)
  {
    $this->id = $id;
    $this->login = $login;
    $this->senha = $senha;
    $this->nome = $nome;
    $this->email = $email;
    $this->tipo = $tipo;
  }

  public function getId()
  {
    return $this->id;
  }
  public function setId($id)
  {
    $this->id = $id;
  }

  public function getLogin()
  {
    return $this->login;
  }
  public function setLogin($login)
  {
    $this->login = $login;
  }

  public function getNome()
  {
    return $this->nome;
  }
  public function setNome($nome)
  {
    $this->nome = $nome;
  }

  public function getSenha()
  {
    return $this->senha;
  }
  public function setSenha($senha)
  {
    $this->senha = $senha;
  }

  public function getEmail()
  {
    return $this->email;
  }
  public function setEmail($email)
  {
    $this->email = $email;
  }

  public function getTipo()
  {
    return $this->tipo;
  }

  public function setTipo($tipo)
  {
    $this->tipo = $tipo;
  }
}

?>