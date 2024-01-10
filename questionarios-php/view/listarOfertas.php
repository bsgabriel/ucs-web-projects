<?php
include_once("components/cabecalho.php");
include_once("components/menuNav.php");
?>
<div class='container mt-5 text-center'>
  <h1>Ofertas</h1>
  <div id="ofertas" class="list-group list-group-flush mt-5">
  </div>
  <?php 
  include_once("components/paginacao.php");
  ?>
</div>
<script src="js/listarOfertas.js"></script>
<?php
include_once("components/rodape.php");
?>