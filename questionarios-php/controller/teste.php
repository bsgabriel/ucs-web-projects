<?php
include_once("fachada.php");


try {
 // $arrUsuarios = array();
 // $usuarios = $factory->getUsuarioDao()->buscarRespondentes();
 // foreach ($usuarios as $usuario) {
 //  $arrUsuarios[] = $usuario->toJSon();
 // }
 // echo json_encode(["status" => "success", "usuarios" => $arrUsuarios]);

 $respondente = new Respondente(null, "teste_respondente", "senha", "nome", "email", "telefone");
 $elaborador = new Elaborador(null, "teste_elaborador", "senha", "nome", "email", "instituição");
 $admin = new Administrador(null, "teste_admin", "senha", "nome", "email");
 $factory->getUsuarioDao()->inserir($admin);
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