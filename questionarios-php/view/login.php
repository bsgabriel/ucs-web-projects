<?php
include_once("components/cabecalho.php");
?>

<section class="container mt-5 text-center">
 <div class="card bg-light py-5">
  <div class="card-body">
   <form action="" id="formLogin">
    <legend>Informe seus dados de login</legend>
    <div class="form-group">
     <label for="login">Nome de usuário:</label>
     <input type="text" class="form-control" id="login" name="login" />
    </div>
    <div class="form-group">
     <label for="senha">Senha:</label>
     <input type="password" class="form-control" id="senha" name="senha" />
    </div>
    <button type="submit" class="btn btn-primary">Login</button><br/>
    <a href="formUsuario.php?tipoUsuario=R" class="btn btn-link mt-3">Cadastrar</a>
   </form>
  </div>
 </div>
</section>
<script type="text/javascript" src="js/login.js"></script>

<?php
include_once("components/modal.php");
include_once("components/rodape.php");
?>
