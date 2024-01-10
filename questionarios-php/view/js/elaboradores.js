$(document).ready(() => {
  carregarPaginacao();
  carregarElaboradores(0);
});

function carregarElaboradores(pagina) {
  $("tbody").empty();
  const url = "../controller/buscarElaboradores.php?start=" + pagina + "&limit=10";
  $.get(url, (data) => {
    const elaboradores = JSON.parse(data);
    elaboradores.forEach((elaborador) => {
      criarLinha(elaborador);
    });
  });
}

function carregarPaginacao() {
  const url = "../controller/totalElaboradores.php";
  $.get(url, (data) => {
    const quantidade = JSON.parse(data).total;
    if (quantidade > 10) {
      criarBotoesPaginacao(quantidade);
    } else {
      $("#paginacao").remove();
    }
  });
}

function criarBotoesPaginacao(qtdElaboradores) {
  const qtdPaginas = Math.ceil(qtdElaboradores / 10);
  const divPaginas = document.getElementById("paginacao");

  for (let i = 1; i <= qtdPaginas; i++) {
    const paginaElemento = document.createElement("li");
    const link = document.createElement("a");
    link.innerText = i;
    link.classList.add("page-link");
    paginaElemento.classList.add("page-item");
    paginaElemento.addEventListener("click", function () {
      carregarElaboradores((i - 1) * 10);
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

function pesquisarElaboradores() {
  $("tbody").empty();
  let data;
  data = {
    pesquisa: $("#pesquisa").val(),
  };
  $.get("../controller/filtrarElaboradores.php", data, (data) => {
    const elaboradores = JSON.parse(data);
    elaboradores.forEach((elaborador) => {
      criarLinha(elaborador);
    });
  });
}

function criarLinha(elaborador) {
  const table = document.getElementById("tabelaElaboradores");
  const row = table.getElementsByTagName("tbody")[0].insertRow(table.length);

  const nome = row.insertCell(0);
  const usuario = row.insertCell(1);
  const email = row.insertCell(2);
  const instituicao = row.insertCell(3);
  const editar = row.insertCell(4);
  const excluir = row.insertCell(5);

  nome.innerHTML = elaborador.nome;
  usuario.innerHTML = elaborador.login;
  email.innerHTML = elaborador.email;
  instituicao.innerHTML = elaborador.instituicao;
  editar.appendChild(createLinkEdicao(elaborador.id));
  excluir.appendChild(createLinkExclusao(elaborador.id));

  editar.classList.add("text-center");
  excluir.classList.add("text-center");
}

function createLinkExclusao(id) {
  const link = createLink("Excluir", "../controller/excluirUsuario.php?id=" + id + "&redirect=../view/elaboradores.php");
  link.onclick = (event) => {
    if (!confirm("Confirmar exclusão?")) {
      event.preventDefault();
    }
  };
  link.classList.add("btn");
  link.classList.add("btn-danger");
  return link;
}

function createLinkEdicao(id) {
  const link = createLink("Editar", "formUsuario.php?tipoUsuario=E&codUsuario=" + id + "&redirect=../view/elaboradores.php");
  link.classList.add("btn");
  link.classList.add("btn-secondary");
  return link;
}

function createLink(text, url) {
  const link = document.createElement("a");
  link.setAttribute("href", url);
  link.appendChild(document.createTextNode(text));

  return link;
}
