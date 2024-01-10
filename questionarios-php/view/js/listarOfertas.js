$(document).ready(() => {
  carregarPaginacao();
  ofertas(0);
});

function ofertas(pagina) {
  $("#ofertas").empty();
  const idUsuario = getCookie("ID_USUARIO_AUTENTICADO");
  const url = "../controller/buscarOfertas.php?limit=3&start=" + pagina + "&idUsuario=" + idUsuario;
  $.get(url, (data) => {
    const ofertas = JSON.parse(data);
    ofertas.forEach((oferta) => {
      mostrarOferta(oferta);
    });
  });
}

function carregarPaginacao() {
  const idUsuario = getCookie("ID_USUARIO_AUTENTICADO");
  const url = "../controller/totalOfertasRespondente.php?idUsuario=" + idUsuario;
  $.get(url, (data) => {
    const quantidade = JSON.parse(data).total;
    if (quantidade > 3) {
      criarBotoesPaginacao(quantidade);
    }
  });
}

function criarBotoesPaginacao(qtdElaboradores) {
  const qtdPaginas = Math.ceil(qtdElaboradores / 3);
  const divPaginas = document.getElementById("paginacao");

  for (let i = 1; i <= qtdPaginas; i++) {
    const paginaElemento = document.createElement("li");
    const link = document.createElement("a");
    link.innerText = i;
    link.classList.add("page-link");
    paginaElemento.classList.add("page-item");
    paginaElemento.addEventListener("click", function () {
      ofertas((i - 1) * 3);
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

function mostrarOferta(oferta) {
  const ofertaQuest = document.createElement("li");
  ofertaQuest.classList.add("list-group-item");
  const card = criarCardQuestionario(oferta);
  ofertaQuest.appendChild(card);
  $("#ofertas").append(ofertaQuest);
}

function criarCardQuestionario(oferta) {
  const card = document.createElement("div");
  card.classList.add("card");

  const header = document.createElement("div");
  header.classList.add("card-header");
  let interior = document.createElement("h4");
  interior.textContent = oferta.questionario.nome;
  header.appendChild(interior);
  card.appendChild(header);

  const body = document.createElement("div");
  body.classList.add("card-body");
  body.textContent = oferta.questionario.descricao;
  card.appendChild(body);

  const footer = document.createElement("div");
  footer.classList.add("card-footer");
  interior = document.createElement("a");
  interior.classList.add("btn");
  interior.classList.add("btn-primary");
  interior.textContent = "Responder";
  interior.href = "responderQuestionario.php?questionario=" + oferta.questionario.id; // TODO: ver outra alternativa para não deixar id exposto
  footer.appendChild(interior);
  card.appendChild(footer);

  return card;
}
