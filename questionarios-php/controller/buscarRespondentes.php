<?php
include_once("fachada.php");
$start = isset($_GET["start"]) ? addslashes(trim($_GET["start"])) : FALSE;
$limit = isset($_GET["limit"]) ? addslashes(trim($_GET["limit"])) : FALSE;

$respondentes = null;
if (!$start && !$limit)
 $respondentes = $factory->getUsuarioDao()->buscarRespondentes();
else
 $respondentes = $factory->getUsuarioDao()->buscarRespondentesOffset($start, $limit);

$list = array();
foreach ($respondentes as $respondente) {
 $list[] = array(
  "id" => $respondente->getId(),
  "nome" => $respondente->getNome(),
  "login" => $respondente->getLogin(),
  "senha" => $respondente->getSenha(),
  "email" => $respondente->getEmail(),
  "telefone" => $respondente->getTelefone(),
  "tipo" => $respondente->getTipo()
 );
}
echo json_encode($list);
?>