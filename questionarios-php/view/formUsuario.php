<?php
include_once("components/cabecalho.php");
include_once("components/menuNav.php");
?>

<section class="container">
  <h1 id="lblTitulo"></h1>
  <form action="" id="formCadastroUsuario">
    <div class="form-group">
      <label for="login">Login:</label>
      <input type="text" class="form-control" id="login" name="login" />
    </div>
    <div class="form-group">
      <label for="senha">Senha:</label>
      <input type="password" class="form-control" id="senha" name="senha" />
    </div>
    <div class="form-group">
      <label for="senhaConfirma">Confirmar senha:</label>
      <input type="password" class="form-control" id="senhaConfirma" name="senhaConfirma" />
    </div>
    <div class="form-group">
      <label for="nome">Nome:</label>
      <input type="text" class="form-control" id="nome" name="nome" />
    </div>
    <div class="form-group">
      <label for="email">Email:</label>
      <input type="text" class="form-control" id="email" name="email" />
    </div>
    <div class="form-group">
      <label for="extra" id="lblCampoExtra"></label>
      <input type="text" class="form-control" id="extra" name="extra" />
    </div>
    <button type="submit" class="btn btn-primary" id="btnSalvarCadastro"></button>
  </form>
</section>
<script type="text/javascript" src="js/formUsuario.js"></script>

<?php
include_once("components/modal.php");
include_once("components/rodape.php");
?>