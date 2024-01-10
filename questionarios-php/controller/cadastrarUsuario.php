<?php

$codUsuario = isset($_POST["codUsuario"]) ? addslashes(trim($_POST["codUsuario"])) : FALSE;
$tipoUsuario = isset($_POST["tipoUsuario"]) ? addslashes(trim($_POST["tipoUsuario"])) : FALSE;
$login = isset($_POST["login"]) ? addslashes(trim($_POST["login"])) : FALSE;
$senha = isset($_POST["senha"]) ? md5(trim($_POST["senha"])) : FALSE;
$nome = isset($_POST["nome"]) ? addslashes(trim($_POST["nome"])) : FALSE;
$email = isset($_POST["email"]) ? addslashes(trim($_POST["email"])) : FALSE;
$extra = isset($_POST["extra"]) ? addslashes(trim($_POST["extra"])) : FALSE;

include_once "fachada.php";

/* ------ verificação de nome de usuário já existente ------ */
$usuarioExistente = $factory->getUsuarioDao()->buscarPorLogin($login);

if (!is_null($usuarioExistente)) {

  // se for inserção, não há ID para comparar
  if (!$codUsuario) {
    $response = array(
      "status" => "error",
      "message" => "Nome de usuario já está sendo usado",
      "tipoCadastro" => "Inserção"
    );
    echo json_encode($response);
    exit;
  }

  // se for alteração, compara o código de usuário que está sendo editado com o que foi retornado da busca
  if (strcmp($codUsuario, $usuarioExistente->getId()) != 0) {
    $response = array(
      "status" => "error",
      "message" => "Nome de usuario já está sendo usado",
      "tipoCadastro" => "Alteração"
    );
    echo json_encode($response);
    exit;
  }
}

/* ------ Alteração ------ */
if ($codUsuario) {
  $usuario = null;
  if (strcmp($tipoUsuario, "E") == 0)
    $usuario = new Elaborador($codUsuario, $login, $senha, $nome, $email, $extra);
  else if (strcmp($tipoUsuario, "R") == 0)
    $usuario = new Respondente($codUsuario, $login, $senha, $nome, $email, $extra);

  $factory->getUsuarioDao()->alterar($usuario);

  $response = array(
    "status" => "success",
    "message" => "Alteração realizada com sucesso!",
    "tipoCadastro" => "Alteração"
  );
  echo json_encode($response);
  exit;
}

/* ----- Inserção ----- */
$usuario = null;
if (strcmp($tipoUsuario, "E") == 0)
  $usuario = new Elaborador($codUsuario, $login, $senha, $nome, $email, $extra);
else if (strcmp($tipoUsuario, "R") == 0)
  $usuario = new Respondente($codUsuario, $login, $senha, $nome, $email, $extra);

$factory->getUsuarioDao()->inserir($usuario);

$response = array(
  "status" => "success",
  "message" => "Cadastro realizado com sucesso!",
  "tipoCadastro" => "Inserção"
);
echo json_encode($response);
exit;
?>