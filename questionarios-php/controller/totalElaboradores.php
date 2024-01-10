<?php
include_once("fachada.php");
$total = $factory->getUsuarioDao()->buscarElaboradores();

$response = array(
 "total" => sizeof($total),
);
echo json_encode($response);
?>