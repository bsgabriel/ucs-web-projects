var form_data = new FormData();

$(document).ready(() => {
  $("#formQuestao").submit((event) => {
    event.preventDefault();
    cadastrarQuestao();
  });
});

function cadastrarQuestao() {
  if (!validarCampos()) {
    return;
  }

  const tipoQuestao = $("input[name='tipo']:checked").val();

  form_data.append("arquivo", $("#arquivo").prop("files")[0]);
  form_data.append("enunciado", $("#descricao").val());
  form_data.append("tipoQuestao", tipoQuestao);

  // JSON
  var jsonAlternativas, alternativas;

  if (tipoQuestao === "M") {
    alternativas = gerarQuestaoMultiplasEscolhas();
  } else if (tipoQuestao === "V") {
    alternativas = gerarQuestaoVerdadeiroFalso();
  }

  jsonAlternativas = JSON.stringify(alternativas);
  console.log(alternativas);
  console.log(jsonAlternativas);

  form_data.append("respostasJSON", jsonAlternativas);

  $.ajax({
    url: "../controller/cadastrarQuestao.php",
    dataType: "text",
    cache: false,
    contentType: false,
    processData: false,
    data: form_data,
    type: "post",
    success: function (response) {
      $("#msg").html(response);
      window.location.href = "menuInicial.php";
    },
    error: function (response) {
      $("#msg").html(response);
    },
  });
}

function validarCampos() {
  if (isEmpty($("#descricao").val())) {
    exibirPopup("Preencha o enunciado da questão");
    return false;
  }

  if (!$("input[name='tipo']:checked").val()) {
    exibirPopup("Escolha o tipo da questão");
    return false;
  }

  const respostasMulti = $("input[name='correta']:checked");
  const tipQuestao = $("input[name='tipo']:checked").val();

  if (tipQuestao === "M" && respostasMulti.length === 0) {
    exibirPopup("Selecione pelo menos uma resposta correta");
    return false;
  }

  const respostaVF = $("input[name='resposta']:checked");
  if (tipQuestao === "V" && respostaVF.length === 0) {
    exibirPopup("Indique se a questão é verdadeira ou falsa");
    return false;
  }

  return true;
}
function gerarQuestaoMultiplasEscolhas() {
  const corretas = $.map($("input[name='correta']:checked"), (elem, idx) => {
    return $(elem).val();
  });

  const erradas = $.map($("input[name='correta']:not(:checked)"), (elem, idx) => {
    return $(elem).val();
  });

  const arrAlternativas = [];

  corretas.forEach((correta) => {
    const fieldName = correta.replace("_", "");
    arrAlternativas.push({
      texto: $("input[name='" + fieldName + "']").val(),
      correta: true,
    });
  });

  erradas.forEach((errada) => {
    const fieldName = errada.replace("_", "");
    arrAlternativas.push({
      texto: $("input[name='" + fieldName + "']").val(),
      correta: false,
    });
  });

  return arrAlternativas;
}

function gerarQuestaoVerdadeiroFalso() {
  const arrAlternativas = [];
  const isVerdade = $("input[id='verdade']").is(":checked");

  arrAlternativas.push({
    texto: $("label[for='verdade']").text().replaceAll("\n", ""),
    correta: isVerdade,
  });

  arrAlternativas.push({
    texto: $("label[for='mentira']").text().replaceAll("\n", ""),
    correta: !isVerdade,
  });

  for (var i = 0; i < arrAlternativas.length; i++) {
    form_data.append("array[]", arrAlternativas[i]);
  }

  return arrAlternativas;
}

function gerarQuestaoDiscursiva() {
  return {
    tipoQuestao: "D",
    enunciado: $("#descricao").val(),
  };
}
/* ---- controles do formulário ----- */
function pegaTipo(src) {
  let tipo = src.value;
  let s = preencheForm(tipo);
  $("#formTipo").html(s);
}

function preencheForm(tipo) {
  if (tipo == "V") {
    let s = "<p>Qual a resposta correta?</p>\n";
    s += "<div class='form-check'>\n";
    s += "<label class='form-check-label' for='verdade'>\n";
    s += "<input type='radio' id='verdade' name='resposta' value='true'/>Verdadeiro\n";
    s += "</label>\n";
    s += "</div>\n";
    s += "<div class='form-check'>\n";
    s += "<label class='form-check-label' for='mentira'>\n";
    s += "<input type='radio' id='mentira' name='resposta' value='false'/>Falso\n";
    s += "</label>\n";
    s += "</div>\n";
    return s;
  }
  if (tipo == "M") {
    let s = "<p>Qual as opções? Marque a(s) correta(s).</p>\n";
    s += "<div class='form-group'>\n";
    s += "<button class='btn btn-secondary' id='addOp' onclick='adicionarOpcao(3)'>Adicionar opção</button>\n";
    s += "<button class='btn btn-secondary' id='delOp' onclick='removerOpcao(3)'>Remover opção</button>\n";
    s += "</div>";

    s += "<div class='input-group mb-3'>\n";
    s += "<div class='input-group-prepend'>\n";
    s += "<div class='input-group-text'>\n";
    s += "<input type='checkbox' name='correta' value='r_1' />\n";
    s += "</div>\n";
    s += "</div>\n";
    s += "<input id='r1' name='r1' type='text' class='form-control'>";
    s += "</div>\n";

    s += "<div class='input-group mb-3'>\n";
    s += "<div class='input-group-prepend'>\n";
    s += "<div class='input-group-text'>\n";
    s += "<input type='checkbox' name='correta' value='r_2' />\n";
    s += "</div>\n";
    s += "</div>\n";
    s += "<input id='r2' name='r2' type='text' class='form-control'>";
    s += "</div>\n";
    return s;
  }
  if (tipo == "D") {
    let s = "";
    return s;
  }
}

function adicionarOpcao(n) {
  let s = "<div class='input-group mb-3'>\n";
  s += "<div class='input-group-prepend'>\n";
  s += "<div class='input-group-text'>\n";
  s += "<input type='checkbox' name='correta' value='r_" + n + "' />\n";
  s += "</div>\n";
  s += "</div>\n";
  s += "<input id='r" + n + "' name='r" + n + "' type='text' class='form-control' required>";
  s += "</div>\n";
  $(".input-group:last").after(s);
  $("#addOp").attr("onclick", "adicionarOpcao(" + (n + 1) + ")");
  $("#delOp").attr("onclick", "removerOpcao(" + (n + 1) + ")");
}

function removerOpcao(n) {
  if (n > 3) {
    $(".input-group:last").remove();
    $("#addOp").attr("onclick", "adicionarOpcao(" + (n - 1) + ")");
    $("#delOp").attr("onclick", "removerOpcao(" + (n - 1) + ")");
  }
}
