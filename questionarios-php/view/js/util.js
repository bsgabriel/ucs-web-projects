function isEmpty(value) {
  return !value || value.trim() === "";
}

function getUrlParameter(sParam) {
  let sPageURL = window.location.search.substring(1),
    sURLVariables = sPageURL.split("&"),
    sParameterName,
    i;

  for (i = 0; i < sURLVariables.length; i++) {
    sParameterName = sURLVariables[i].split("=");
    if (sParameterName[0] === sParam) {
      return sParameterName[1] === undefined ? "" : decodeURIComponent(sParameterName[1]);
    }
  }
  return "";
}

function exibirPopup(message) {
  $("#mdlDescricao").text(message);
  $('#janelaModal').modal('show');
}

function getCookie(cookieName) {

  const cookie = {};
  document.cookie.split(";").forEach((element) => {
    const [key, value] = element.split("=");
    cookie[key.trim()] = value;
  });
  return cookie[cookieName];
}