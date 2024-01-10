$(document).ready(() => {
  $("#formLogin").submit((event) => {
    event.preventDefault();
    loginEvent();
  });
});

function loginEvent() {
  const usuario = $("#login").val();
  const senha = $("#senha").val();

  if (isEmpty(usuario)) {
    exibirPopup("Informe o usuário");
    return false;
  }

  if (isEmpty(senha)) {
    exibirPopup("Informe a senha");
    return false;
  }

  executarLogin(usuario, senha);
}

function executarLogin(usuario, senha) {
  $.post(
    "../controller/efetuarLogin.php",
    { login: usuario, senha: senha },
    function (response) {
      if (response.status === "success") {
        window.location.href = "menuInicial.php";
      } else {
        exibirPopup(response.message);
        console.error(response.message);
        if (response.hasOwnProperty("stackTrace")) {
          console.error(response.stackTrace);
        }
      }
    },
    "json"
  ).fail(function (xhr, status, error) {
    console.error(error);
  });
}
