<?php
include_once("components/cabecalho.php");
include_once("components/menuNav.php");
?>

<section class="container">
  <div class="container mt-5">
    <?php
    include_once("components/pesquisa.php");
    ?>
    <h1>Elaboradores</h1>
    <div class="table-responsive">
      <table class="table table-sm" id="tabelaElaboradores">
        <thead>
          <tr>
            <th scope="col">Nome</th>
            <th scope="col">Usuário</th>
            <th scope="col">E-mail</th>
            <th scope="col">Instituição</th>
            <th scope="col" class="text-center">Editar</th>
            <th scope="col" class="text-center">Excluir</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
      <?php
      include_once("components/paginacao.php");
      ?>
      <a class="btn btn-primary justify-content-end" id="btnNovoElaborador"
        href="formUsuario.php?tipoUsuario=E&redirect=../view/elaboradores.php">Novo elaborador</a>
    </div>
  </div>
</section>
<script type="text/javascript" src="js/elaboradores.js"></script>

<?php
include_once("components/rodape.php");
?>