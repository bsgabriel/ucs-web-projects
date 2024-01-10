let tipoUsuario;
let codUsuario;
let redirect;

$(document).ready(() => {
  tipoUsuario = getUrlParameter("tipoUsuario");
  codUsuario = getUrlParameter("codUsuario");

  const urlRedirect = getUrlParameter("redirect");
  redirect = !isEmpty(urlRedirect) ? urlRedirect : "menuInicial.php";

  $("#lblTitulo").text(tipoUsuario === "E" ? "Elaborador" : "Respondente");

  // carrega o label do campo extra dependendo do tipo de usuário
  $("#lblCampoExtra").text(tipoUsuario === "E" ? "Instituição:" : "Telefone:");

  // carrega o texto do botão dependendo da ação a ser executada
  $("#btnSalvarCadastro").text(!isEmpty(codUsuario) ? "Salvar" : "Cadastrar");

  if (!isEmpty(codUsuario)) {
    buscarUsuario();
  }

  $("#formCadastroUsuario").submit((event) => {
    event.preventDefault();

    if (!validarCampos()) {
      return;
    }

    submitEvent();
  });
});

function submitEvent() {
  let data;
  if (isEmpty(codUsuario)) {
    data = {
      tipoUsuario: tipoUsuario,
      login: $("#login").val(),
      senha: $("#senha").val(),
      nome: $("#nome").val(),
      email: $("#email").val(),
      extra: $("#extra").val(),
    };
  } else {
    data = {
      codUsuario: codUsuario,
      tipoUsuario: tipoUsuario,
      login: $("#login").val(),
      senha: $("#senha").val(),
      nome: $("#nome").val(),
      email: $("#email").val(),
      extra: $("#extra").val(),
    };
  }

  $.post(
    "../controller/cadastrarUsuario.php",
    data,
    function (response) {
      exibirPopup(response.message);
      if (response.status === "success") {
        if (response.tipoCadastro === "Inserção" && tipoUsuario === "R") {
          window.location.href = "login.php";
        } else {
          window.location.href = redirect;
        }
      }
    },
    "json"
  ).fail(function (xhr, status, error) {
    console.error(error);
  });
}

function buscarUsuario() {
  $.get("../controller/buscarUsuario.php?codUsuario=" + codUsuario, (data) => {
    const usuario = JSON.parse(data);
    $("#login").val(usuario.login);
    $("#nome").val(usuario.nome);
    $("#email").val(usuario.email);
    $("#extra").val(usuario.campoExtra);
  });
}

function validarCampos() {
  const msg = "É necessário informar ";

  if (isEmpty($("#login").val())) {
    exibirPopup(msg + "o usuário");
    return false;
  }

  if (isEmpty($("#senha").val())) {
    exibirPopup(msg + "a senha");
    return false;
  }

  if (isEmpty($("#senhaConfirma").val())) {
    exibirPopup("É necessário confirmar a senha");
    return false;
  }

  if (isEmpty($("#nome").val())) {
    exibirPopup(msg + "o nome");
    return false;
  }

  if (isEmpty($("#email").val())) {
    exibirPopup(msg + "o email");
    return false;
  }

  if (isEmpty($("#extra").val())) {
    const extra = $("#lblCampoExtra").text(tipoUsuario === "E" ? "a instituição" : "o telefone");
    exibirPopup(msg + extra);
    return false;
  }

  if ($("#senha").val() !== $("#senhaConfirma").val()) {
    exibirPopup("As senhas não coincidem");
    return false;
  }

  return true;
}
