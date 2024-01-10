<?php
$pesquisa = $_GET["pesquisa"];

include_once("fachada.php");
$elaboradores = $factory->getUsuarioDao()->buscarPorNomeEmail($pesquisa, 'E');

$list = array();
foreach ($elaboradores as $elaborador) {
 $list[] = array(
  "id" => $elaborador->getId(),
  "nome" => $elaborador->getNome(),
  "login" => $elaborador->getLogin(),
  "senha" => $elaborador->getSenha(),
  "email" => $elaborador->getEmail(),
  "instituicao" => $elaborador->getInstituicao(),
  "tipo" => $elaborador->getTipo()
 );
}
echo json_encode($list);
?>