$(document).ready(() => {
  questoes();
});

function questoes() {
  const idQuestionario = getUrlParameter("questionario");
  const url = "../controller/buscarQuestionario.php?idQuestionario=" + idQuestionario;

  $.get(url, (data) => {
    const questionario = JSON.parse(data);
    $("#lblNomeQuestionario").text(questionario.nome);
    questionario.questoes.forEach((questao) => {
      mostraQuestao(questao);
    });
    botaoFinalizar();
  });
}

function botaoFinalizar() {
  const fim = document.createElement("div");
  fim.classList.add("form-group");
  fim.classList.add("mt-5");

  const botaoFim = document.createElement("button");
  botaoFim.textContent = "Finalizar";
  botaoFim.classList.add("btn");
  botaoFim.classList.add("btn-primary");
  botaoFim.addEventListener("click", () => {
    console.warn("envio de respostas ainda não implementado !!!!");
  });

  fim.append(botaoFim);
  $("#questionario").append(fim);
}

function mostraQuestao(questao) {
  const pergunta = document.createElement("div");
  pergunta.classList.add("form-group");
  pergunta.classList.add("pt-4");

  // Precisa de testes com imagens do banco de dados
  /*if (questao.imagem != null) {
		const image = document.createElement("img");
		image.classList.add("img-fluid");
		image.src = questao.imagem;
	}*/

  pergunta.appendChild(enunciado(questao.descricao));
  if (questao.tipo == "D") {
    let conteudo = descritiva(questao);
    pergunta.appendChild(conteudo);
  } else if (questao.tipo == "V") {
    vf(pergunta, questao);
  } else if (questao.tipo == "M") {
    multiplaEscolha(pergunta, questao, questao.alternativas);
  } else {
    return;
  }
  $("#questionario").append(pergunta);
}

function enunciado(descricao) {
  const enunciado = document.createElement("label");
  enunciado.textContent = descricao;
  return enunciado;
}

function descritiva(questao) {
  const txtArea = document.createElement("textarea");
  txtArea.name = questao.id;
  txtArea.classList.add("form-control");
  txtArea.rows = "5";
  return txtArea;
}

function vf(pergunta, questao) {
  pergunta.appendChild(opcaoUnica(questao.id, "Verdadeiro", "true"));
  pergunta.appendChild(opcaoUnica(questao.id, "Falso", "false"));
}

function multiplaEscolha(pergunta, questao, alternativas) {
  let nCorretas = qtdCorretas(alternativas);
  if (nCorretas > 1) {
    for (let alt of alternativas) {
      pergunta.appendChild(opcaoMultipla(questao.id, alt.descricao, alt.id));
    }
  } else {
    for (let alt of alternativas) {
      pergunta.appendChild(opcaoUnica(questao.id, alt.descricao, alt.id));
    }
  }
}

function qtdCorretas(alternativas) {
  let nCorretas = 0;
  for (alt of alternativas) {
    if (alt.correta) {
      nCorretas++;
    }
  }
  return nCorretas;
}

function opcaoUnica(id, texto, valor) {
  const opcao = document.createElement("div");
  opcao.classList.add("form-check");
  const txt = document.createElement("label");
  txt.classList.add("form-check-label");
  const alt = "<input type='radio' name='" + id + "' class='form-check-input' value='" + valor + "'>" + texto;
  txt.innerHTML = alt;
  opcao.appendChild(txt);
  return opcao;
}

function opcaoMultipla(id, texto, valor) {
  const opcao = document.createElement("div");
  opcao.classList.add("form-check");
  const txt = document.createElement("label");
  txt.classList.add("form-check-label");
  const alt = "<input type='checkbox' name='" + id + "' class='form-check-input' value='" + valor + "'>" + texto;
  txt.innerHTML = alt;
  opcao.appendChild(txt);
  return opcao;
}
