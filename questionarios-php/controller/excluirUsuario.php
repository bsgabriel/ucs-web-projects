<?php
include_once("fachada.php");
$factory->getUsuarioDao()->removerPorId($_GET["id"]);
header("Location: " . $_GET["redirect"]);
?>