<?php
include_once("components/cabecalho.php");
include_once("components/menuNav.php");
?>

<div class='container mt-5 text-center'>
  <h1>Questionários</h1>
  <div id="principal" class="mt-5"></div>
  <?php
  include_once("components/paginacao.php");
  ?>
  <div id="botoes" class="mt-3"></div>
</div>

<script type="text/javascript" src="js/oferecerQuestionario.js"></script>

<?php
include_once("components/modal.php");
include_once("components/rodape.php");
?>