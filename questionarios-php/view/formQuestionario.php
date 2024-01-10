<?php
include_once("components/cabecalho.php");
include_once("components/menuNav.php");
?>

<section class="container">
  <div class="container mt-5">
    <h1>Cadastro de Questionário</h1>
    <form action="" id="formQuestionario">
      <div class="form-group">
        <label for="nome">Nome do questionário:</label>
        <input type="text" class="form-control" id="nome" name="nome" />
      </div>
      <div class="form-group">
        <label for="descricao">Descrição:</label>
        <textarea class="form-control" rows="5" id="descricao" name="descricao"></textarea>
      </div>
      <div class="form-group">
        <label for="notaAprovacao">Nota mínima para aprovação:</label>
        <input type="text" class="form-control" id="notaAprovacao" name="notaAprovacao" />
      </div>
      <div class="form-group">
        <a class="btn btn-secondary">Cancelar</a>
        <button type="submit" class="btn btn-primary ml-1">Criar</button>
      </div>
      <div class="col">
        <div class="form-group row">
          <table id="presentes" class="table table-bordered text-center table-sm">
            <thead>
              <tr>
                <th colspan="3">Presentes</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
        <div class="form-group row">
          <table id="disponiveis" class="table table-bordered text-center table-sm">
            <thead>
              <tr>
                <th colspan="2">Disponíveis</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </form>
  </div>
</section>
<script type="text/javascript" src="js/formQuestionario.js"></script>

<?php
include_once("components/modal.php");
include_once("components/rodape.php");
?>