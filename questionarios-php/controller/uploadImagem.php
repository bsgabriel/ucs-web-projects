<?php
include_once ("fachada.php");

$nome_temporario = $_FILES["Arquivo"]["tmp_name"];
$nome_real = $_FILES["Arquivo"]["name"];
//Substitui os espaços em branco por "_"
$nome_real = str_replace(" ", "_", $nome_real);

copy($nome_temporario, "../uploads/$nome_real");
?>