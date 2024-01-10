<?php
include_once("components/cabecalho.php");
include_once("components/menuNav.php");
?>

<section class="container">
  <div class="container mt-5">
    <h1>Cadastro de Questão</h1>
    <form action="" id="formQuestao" enctype="multipart/form-data">
      <div class="form-group">
        <label for="descricao">Enunciado:</label>
        <textarea class="form-control" rows="5" id="descricao" name="descricao"></textarea>
      </div>
      <div class="form-group">
        <div class="form-check-inline">
          <label class="form-check-label"> <input type="radio" class="form-check-input" name="tipo" value="M"
              onChange="pegaTipo(this)" />Múltipla escolha </label>
        </div>
        <div class="form-check-inline">
          <label class="form-check-label"> <input type="radio" class="form-check-input" name="tipo" value="D"
              onChange="pegaTipo(this)" />Discursiva </label>
        </div>
        <div class="form-check-inline">
          <label class="form-check-label"> <input type="radio" class="form-check-input" name="tipo" value="V"
              onChange="pegaTipo(this)" />Verdadeiro ou falso </label>
        </div>
        <div class="form-check-inline">
          <input type="file" id="arquivo" name="arquivo"/>
        </div>
      </div>
      <div id="formTipo" class="form-group pb-3"></div>
      <a class="btn btn-secondary">Cancelar</a>
      <button type="submit" class="btn btn-primary ml-1">Criar</button>
    </form>
  </div>
  <div id="janelaModal" class="modal fade">
    <div class="modal-dialog modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-body mdl-content">
          <p class="mdl-text-justified" id="mdlDescricao"></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
</section>

<script type="text/javascript" src="js/formQuestao.js"></script>
<?php
include_once("components/modal.php");
include_once("components/rodape.php");
?>