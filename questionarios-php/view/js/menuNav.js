$(document).ready(() => {
  carregarMenu();
});

function carregarMenu() {
  $("#menuNav").append(createItem("Início", "menuInicial.php"));

  // TODO achar outra alternativa para o uso do cookie
  const tipUsuario = getCookie("TIPO_USUARIO_AUTENTICADO");
  const idUsuario = getCookie("ID_USUARIO_AUTENTICADO");

  if (tipUsuario === "A") {
    $("#menuNav").append(createItem("Elaboradores", "elaboradores.php"));
  } else if (tipUsuario === "E") {
    $("#menuNav").append(createItem("Criar questão", "formQuestao.php"));
    $("#menuNav").append(createItem("Criar Questionários", "formQuestionario.php"));
    $("#menuNav").append(createItem("Oferecer Questionário", "oferecerQuestionario.php"));
  } else {
    $("#menuNav").append(createItem("Responder Questionário", "listarOfertas.php"));
    $("#menuNav").append(createItem("Editar Conta", "formUsuario.php?tipoUsuario=R&codUsuario=" + idUsuario));
  }
  $("#menuNav").append(createItem("Logout", "login.php")); // TODO: criar logout.php para limpar cookies, dados de sessão, e retornar à página de login
}

function createItem(text, url) {
  // gera a tag LI
  const item = document.createElement("li");
  item.classList.add("nav-item");

  // gera a tag A
  const link = document.createElement("a");
  link.classList.add("nav-link");
  link.setAttribute("href", url);
  link.appendChild(document.createTextNode(text));

  item.appendChild(link);
  return item;
}
