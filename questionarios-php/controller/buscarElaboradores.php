<?php
try {
 include_once("fachada.php");
 $start = isset($_GET["start"]) ? addslashes(trim($_GET["start"])) : FALSE;
 $limit = isset($_GET["limit"]) ? addslashes(trim($_GET["limit"])) : FALSE;

 $elaboradores = null;
 if (!$start && !$limit)
  $elaboradores = $factory->getUsuarioDao()->buscarElaboradores();
 else
  $elaboradores = $factory->getUsuarioDao()->buscarElaboradoresOffset($start, $limit);

 $list = array();
 foreach ($elaboradores as $elaborador) {
  $list[] = array(
   "status" => "success",
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
} catch (\Throwable $th) {
 $response = array(
  "status" => "error",
  "message" => $th->getMessage(),
  "stackTrace" => $th->getTraceAsString(),
 );
 echo json_encode($response);
 exit;
}
?>