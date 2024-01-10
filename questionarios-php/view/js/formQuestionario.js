const questoesEscolhidas = [];

$(document).ready(() => {
  buscarQuestoes();

  $("#formQuestionario").submit((event) => {
    event.preventDefault();
    if (!validarCampos()) {
      return;
    }

    enviarQuestionario();
  });
});

function buscarQuestoes() {
  $.get("../controller/buscarQuestoes.php", (data) => {
    const questoes = JSON.parse(data);
    questoes.forEach((questao) => {
      criarQuestaoDisponivel(questao);
    });
  });
}

function criarQuestaoDisponivel(questao) {
  const table = document.getElementById("disponiveis");
  const row = table.getElementsByTagName("tbody")[0].insertRow(table.length);

  const colId = row.insertCell(0);
  const colTextArea = row.insertCell(1);
  const colAdicionar = row.insertCell(2);

  colId.innerHTML = questao.idQuestao;
  colId.classList.add("d-none");

  colTextArea.appendChild(createTextArea(questao));

  const btnAdicionar = document.createElement("button");
  btnAdicionar.classList.add("btn");
  btnAdicionar.classList.add("btn-success");
  btnAdicionar.textContent = "Adicionar";
  btnAdicionar.onclick = (event) => {
    criarQuestaoEscolhida(questao);
    row.remove();

    // TODO: analisar de deve ser armazenado apenas o ID ou o objeto inteiro
    questoesEscolhidas.push(questao.idQuestao);
  };

  colAdicionar.classList.add("align-middle");
  colAdicionar.appendChild(btnAdicionar);
}

function criarQuestaoEscolhida(questao) {
  const table = document.getElementById("presentes");
  const row = table.getElementsByTagName("tbody")[0].insertRow(table.length);

  const colId = row.insertCell(0);
  const colTextArea = row.insertCell(1);
  const colValor = row.insertCell(2);
  const colRemover = row.insertCell(3);

  colId.innerHTML = questao.idQuestao;
  colId.classList.add("d-none");

  colTextArea.appendChild(createTextArea(questao));

  const label = document.createElement("label");
  label.setAttribute("for", "valor");
  label.innerHTML = "Valor da questão";

  const input = document.createElement("input");
  input.setAttribute("type", "text");
  input.setAttribute("name", "valor");
  input.setAttribute("id", "valor_" + questao.idQuestao);
  input.classList.add("form-control");

  colValor.classList.add("align-middle");
  colValor.appendChild(label);
  colValor.appendChild(input);

  const btnRemover = document.createElement("button");
  btnRemover.classList.add("btn");
  btnRemover.classList.add("btn-danger");
  btnRemover.textContent = "Remover";
  btnRemover.onclick = (event) => {
    criarQuestaoDisponivel(questao);
    row.remove();

    const index = questoesEscolhidas.indexOf(questao.idQuestao);
    questoesEscolhidas.splice(index, 1);
  };

  colRemover.classList.add("align-middle");
  colRemover.appendChild(btnRemover);
}

function createTextArea(questao) {
  const txtArea = document.createElement("textarea");
  txtArea.classList.add("form-control");
  txtArea.setAttribute("rows", 5);
  txtArea.readOnly = true;

  let txt = "Tipo: ";
  if (questao.tipo === "V") {
    txt = txt.concat("verdadeiro ou falso");
  } else if (questao.tipo === "M") {
    txt = txt.concat("múltiplas escolhas");
  } else if (questao.tipo === "D") {
    txt = txt.concat("dissertativa");
  }

  txt = txt.concat("\n");
  txt = txt.concat("Enunciado: ");
  txt = txt.concat(questao.descricao);

  if (questao.alternativas.length > 0) {
    txt = txt.concat("\n");
    txt = txt.concat("Alternativas: ");

    questao.alternativas.forEach((alternativa) => {
      txt = txt.concat("\n");
      txt = txt.concat("  - ");
      txt = txt.concat(alternativa.descricao);
    });
  }

  txtArea.textContent = txt;
  return txtArea;
}

function validarCampos() {
  if (isEmpty($("#nome").val())) {
    exibirPopup("Informe o nome do questionário");
    return false;
  }

  if (isEmpty($("#descricao").val())) {
    exibirPopup("Informe a descrição do questionário");
    return false;
  }

  if (isEmpty($("#notaAprovacao").val())) {
    exibirPopup("Informe a nota mínima para aprovação");
    return false;
  }

  if (questoesEscolhidas.length === 0) {
    exibirPopup("Selecione pelo menos uma questão");
    return false;
  }

  let semValor = false;
  questoesEscolhidas.forEach((idQUestao) => {
    if (semValor) {
      return;
    }

    if (isEmpty($("#valor_" + idQUestao).val())) {
      semValor = true;
    }
  });

  if (semValor) {
    exibirPopup("Informe o valor de todas as questões");
    return false;
  }

  return true;
}

function enviarQuestionario() {
  const data = {
    codElaborador: getCookie("ID_USUARIO_AUTENTICADO"),
    nome: $("#nome").val(),
    descricao: $("#descricao").val(),
    notaAprovacao: $("#notaAprovacao").val(),
    questoes: gerarQuestoesValores(),
  };

  console.log(JSON.stringify(data));

  $.post(
    "../controller/cadastrarQuestionario.php",
    data,
    function (response) {
      if (response.status === "success") {
        window.location.href = "menuInicial.php";
      } else {
        exibirPopup(response.message);
        console.log(response.stackTrace);
      }
    },
    "json"
  ).fail(function (xhr, status, error) {
    console.error(error);
  });
}

function gerarQuestoesValores() {
  const arrQuestoesValores = [];
  questoesEscolhidas.forEach((idQUestao) => {
    arrQuestoesValores.push({
      idQuestao: idQUestao,
      valorQuestao: $("#valor_" + idQUestao).val(),
    });
  });
  return arrQuestoesValores;
}
