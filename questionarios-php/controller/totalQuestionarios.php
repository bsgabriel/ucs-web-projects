<?php
include_once("fachada.php");
$total = $factory->getQuestionarioDao()->buscarTodos();

$response = array(
 "total" => sizeof($total),
);
echo json_encode($response);

?>