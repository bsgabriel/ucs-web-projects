//Variável que guarda o id do questionário escolhido
var idQuest;
const idTabelaRespondentes = "tblOfertaRespondentes";
const respondentes = [];

$(document).ready(() => {
  defaultValues();
  carregarQuestionarios(0);
  carregarPaginacao();
});

function defaultValues() {
  $("#principal").empty();
  $("#botoes").empty();
  $("h1").html("Questionários");
  document.title = "Questionários";
  idQuest = null;
  respondentes.length = 0;
}

function carregarQuestionarios(pagina) {
  $("#principal").empty();
  $.get("../controller/buscarQuestionarios.php?start=" + pagina + "&limit=10", (data) => {
    const questionarios = JSON.parse(data);
    questionarios.forEach((questionario) => {
      criarLinhaQuestionario(questionario);
    });
  });
}

function carregarRespondentes(pagina) {
  $("tbody").empty();
  $.get("../controller/buscarRespondentes.php?start=" + pagina + "&limit=10", (data) => {
    const respondentes = JSON.parse(data);
    respondentes.forEach((respondente) => {
      criarLinhaRespondente(respondente);
    });
  });
}

function carregarPaginacao() {
  $("#paginacao").empty();
  let url = "";
  if (idQuest == null) {
    url = "../controller/totalQuestionarios.php";
  } else {
    url = "../controller/totalRespondentes.php";
  }

  $.get(url, (data) => {
    const quantidade = JSON.parse(data).total;
    if (quantidade > 10) {
      criarBotoesPaginacao(quantidade);
    }
  });
}

function criarBotoesPaginacao(quantidade) {
  const qtdPaginas = Math.ceil(quantidade / 10);
  const divPaginas = document.getElementById("paginacao");

  for (let i = 1; i <= qtdPaginas; i++) {
    const paginaElemento = document.createElement("li");
    const link = document.createElement("a");
    link.innerText = i;
    link.classList.add("page-link");
    paginaElemento.classList.add("page-item");
    paginaElemento.addEventListener("click", function () {
      const iniPesquisa = (i - 1) * 10;
      if (idQuest == null) {
        carregarQuestionarios(iniPesquisa);
      } else {
        carregarRespondentes(iniPesquisa);
      }

      $(".page-item").each(function (index, element) {
        element.classList.remove("active");
      });
      paginaElemento.classList.add("active");
    });
    paginaElemento.append(link);
    divPaginas.appendChild(paginaElemento);

    if (i == 1) {
      paginaElemento.classList.add("active");
    }
  }
}

function selecionarQuestionario(idQuestionario) {
  idQuest = idQuestionario;
  $("#principal").empty();
  $("h1").html("Oferecer para...");
  document.title = "Oferecer para...";
  criarTabelaRespondente();
  carregarRespondentes(0);
  carregarPaginacao();
}

function criarLinhaQuestionario(questionario) {
  const linhaQuestionario = document.createElement("button");
  linhaQuestionario.classList.add("list-group-item");
  linhaQuestionario.classList.add("list-group-item-action");
  linhaQuestionario.classList.add("list-group-item-light");
  linhaQuestionario.value = questionario.id;
  linhaQuestionario.textContent = questionario.nome;
  linhaQuestionario.addEventListener("click", () => {
    selecionarQuestionario(questionario.id);
  });
  $("#principal").append(linhaQuestionario);
}

function criarTabelaRespondente() {
  const table = document.createElement("table");
  const header = document.createElement("thead");
  const row = document.createElement("tr");
  const colNomeUsuario = document.createElement("td");
  const colNome = document.createElement("td");
  const colEmail = document.createElement("td");
  const btnVoltar = document.createElement("button");
  const btnEnviar = document.createElement("button");

  table.id = idTabelaRespondentes;
  table.classList.add("table");
  table.classList.add("text-center");
  header.classList.add("font-weight-bold");

  colNomeUsuario.textContent = "Nome de usuário";
  colNome.textContent = "Nome";
  colEmail.textContent = "Email";

  btnVoltar.classList.add("btn");
  btnVoltar.classList.add("btn-secondary");
  btnVoltar.classList.add("mr-3");
  btnVoltar.textContent = "Voltar";
  btnVoltar.addEventListener("click", () => {
    voltar();
  });

  btnEnviar.classList.add("btn");
  btnEnviar.classList.add("btn-primary");
  btnEnviar.textContent = "Oferecer";
  btnEnviar.addEventListener("click", () => {
    enviarOferta();
  });

  row.appendChild(document.createElement("td"));
  row.appendChild(colNomeUsuario);
  row.appendChild(colNome);
  row.appendChild(colEmail);
  header.appendChild(row);
  table.appendChild(header);
  table.appendChild(document.createElement("tbody"));

  $("#principal").append(table);
  $("#botoes").append(btnVoltar);
  $("#botoes").append(btnEnviar);
}

function criarLinhaRespondente(respondente) {
  const table = document.getElementById(idTabelaRespondentes);
  const row = table.getElementsByTagName("tbody")[0].insertRow(table.length);

  const colCheck = row.insertCell(0);
  const colUsuario = row.insertCell(1);
  const colNome = row.insertCell(2);
  const colEmail = row.insertCell(3);

  const check = document.createElement("input");
  check.setAttribute("type", "checkbox");
  check.setAttribute("name", "codRespondente");
  check.setAttribute("value", respondente.id);
  check.classList.add("form-check-input");
  check.checked = respondentes.includes(respondente.id);
  check.addEventListener("change", () => {
    const id = check.value;
    if (check.checked) {
      respondentes.push(Number(id));
    } else {
      const index = respondentes.indexOf(Number(id));
      respondentes.splice(index, 1);
    }
  });
  colCheck.appendChild(check);

  colUsuario.innerHTML = respondente.login;
  colNome.innerHTML = respondente.nome;
  colEmail.innerHTML = respondente.email;
}

function voltar() {
  defaultValues();
  carregarQuestionarios(0);
  carregarPaginacao();
}

function enviarOferta() {
  if (!validarSelecao()) {
    return;
  }
  salvarOferta();
}

function validarSelecao() {
  if (!Number.isInteger(idQuest)) {
    exibirPopup("Selecione um questionário");
    return false;
  }

  if (!respondentes.length) {
    exibirPopup("Selecione pelo menos um respondente");
    return false;
  }

  return true;
}

function salvarOferta() {
  const data = {
    codQuestionario: idQuest,
    lstRespondentes: respondentes,
  };
  
  $.post(
    "../controller/gravarOfertaQuestionario.php",
    data,
    function (response) {
      if (response.status === "success") {
        window.location.href = "menuInicial.php";
      } else {
        exibirPopup(response.message);
        console.error(response.stackTrace);
      }
    },
    "json"
  ).fail(function (xhr, status, error) {
    console.error(error);
  });
}
