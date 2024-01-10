<?php

include_once "fachada.php";

if (isset($_FILES["arquivo"])) {
    $nome_temporario = $_FILES["arquivo"]["tmp_name"];
    $nome_real = $_FILES["arquivo"]["name"];
    $nome_real = str_replace(" ", "_", $nome_real);

    $caminhoCompleto = str_replace("controller", "uploads/$nome_real", __DIR__);
    copy($nome_temporario, $caminhoCompleto);
}

$tipoQuestao = isset($_POST["tipoQuestao"]) ? addslashes(trim($_POST["tipoQuestao"])) : FALSE;
$enunciado = isset($_POST["enunciado"]) ? addslashes(trim($_POST["enunciado"])) : FALSE;
$respostas = isset($_POST["respostasJSON"]) ? $_POST["respostasJSON"] : FALSE;

$respostas = json_decode($respostas);
$alternativas = [];
$cont = 1;

if (isset($respostas)) {
    for ($i = 0; $i < count($respostas); $i++) {
        if ($respostas[$i]->correta) {
            $alternativas[] = new Alternativa(null, $respostas[$i]->texto, 'true', null);
        } else {
            $alternativas[] = new Alternativa(null, $respostas[$i]->texto, 'false', null);
        }
    }
}

$questao = new Questao(null, $enunciado, $tipoQuestao, $caminhoCompleto, $alternativas);
$factory->getQuestaoDao()->inserir($questao);

$response = array(
    "status" => "success",
    "message" => "Cadastro de questão realizado com sucesso",
);
echo json_encode($response);
exit;
?>