window.addEventListener("load", function () {
  loadHeader();

  const table = document.getElementById("tabelaCursos");
  loadTable(table);
  addRowHandlers(table);
});

function loadHeader() {
  document.getElementById("nomeCurso").textContent = jsonData.CURSO;
  document.getElementById("codCurso").textContent = jsonData.CODIGO_DO_CURSO;

  const duracao = document.getElementById("infoDuracao");
  const txtDuracao = "Duração: " + jsonData.DURACAO;
  const txtCargaHoraria = "Carga Horária: " + jsonData.CARGA_HORARIA;
  duracao.innerHTML = txtDuracao + "<br>" + txtCargaHoraria;
}

function loadTable(table) {
  for (let j = 0; j < jsonData.DISCIPLINAS.length; j++) {
    const row = table.getElementsByTagName("tbody")[0].insertRow(table.length);
    row.classList.add("content");
    row.setAttribute("data-toggle", "modal");
    row.setAttribute("data-target", "#janelaModal");
    row.setAttribute("data-id", j);

    const semestre = row.insertCell(0);
    const codDisciplina = row.insertCell(1);
    const nomeDisciplina = row.insertCell(2);
    const cargaHoraria = row.insertCell(3);

    semestre.innerHTML = jsonData.DISCIPLINAS[j].SEMESTRE;
    codDisciplina.innerHTML = jsonData.DISCIPLINAS[j].CODIGO;
    nomeDisciplina.innerHTML = jsonData.DISCIPLINAS[j].DISCIPLINA;
    cargaHoraria.innerHTML = jsonData.DISCIPLINAS[j].HORAS + "h";

    semestre.classList.add("numero");
    cargaHoraria.classList.add("numero");

    if (jsonData.DISCIPLINAS[j].SEMESTRE % 2 == 0) {
      row.classList.add("semestre-par");
    } else {
      row.classList.add("semestre-impar");
    }
  }
}

function addRowHandlers(table) {
  Array.from(table.rows).forEach((row) => {
    row.onclick = (function () {
      return function () {
        clearPopUp();
        
        // const id = this.cells[0].innerHTML;
        const id = row.rowIndex - 1;
        const codigo = document.getElementById("modalCodDisciplina");
        const txtCodigo = jsonData.DISCIPLINAS[id].CODIGO;
        codigo.innerHTML = txtCodigo;

        const nome = document.getElementById("modalNomeDisciplina");
        nome.innerHTML = jsonData.DISCIPLINAS[id].DISCIPLINA;

        const ementa = document.getElementById("modalEmenta");
        // checa se é materia optativa (começa com FGU)
        if (txtCodigo.startsWith("FGU")) {
          ementa.innerHTML = "Disciplina optativa fica à escolha do aluno.";
        } else {
          ementa.innerHTML = jsonData.DISCIPLINAS[id].EMENTA;
        }

        const outros = document.getElementById("modalOutros");
        const txtSemestre = jsonData.DISCIPLINAS[id].SEMESTRE + "º Semestre";
        const txtDuracao =
          "Duração " + jsonData.DISCIPLINAS[id].HORAS + " horas";
        let txtModalidade = "Modalidade ";

        const nat = jsonData.DISCIPLINAS[id].NAT;

        if (nat === "FBP" || nat === "FEP") {
          txtModalidade = txtModalidade.concat("presencial");
        } else {
          txtModalidade = txtModalidade.concat("EAD");
        }

        outros.innerHTML =
          txtSemestre + " - " + txtModalidade + " - " + txtDuracao;

        const preRequisitos = jsonData.DISCIPLINAS[id].PREREQUISITOS;

        if (typeof preRequisitos !== "undefined") {
          const lblPreRequisitos = document.getElementById("lblPreRequisitos");
          lblPreRequisitos.textContent = "Pré-requisitos";
          createGrid(preRequisitos);
        }
      };
    })(row);
  });
}

function createGrid(requisitos) {
  if (typeof requisitos === "number") {
    createGridItem(requisitos - 1);
    return;
  }

  if (typeof requisitos === "string") {
    requisitos = requisitos.replaceAll(" ", "");
    if (requisitos.includes(",")) {
      arrRequisitos = requisitos.split(",").map(Number);
      arrRequisitos.forEach((req) => {
        createGridItem(req - 1);
      });
    } else if (requisitos.includes("+")) {
      createGridItem(requisitos.split("+")[0] - 1);
    }
    return;
  }
}

function createGridItem(ordem) {
  const grid = document.getElementById("gridPreRequisitos");
  const item = document.createElement("div");

  const codDisciplina = document.createElement("p");
  codDisciplina.textContent = jsonData.DISCIPLINAS[ordem].CODIGO;

  const nomeDisciplina = document.createElement("p");
  nomeDisciplina.textContent = jsonData.DISCIPLINAS[ordem].DISCIPLINA;

  item.classList.add("grid-item");
  item.appendChild(codDisciplina);
  item.appendChild(nomeDisciplina);
  grid.appendChild(item);
  return item;
}

function clearPopUp() {
  const lblPreRequisitos = document.getElementById("lblPreRequisitos");
  lblPreRequisitos.textContent = "";

  const grid = document.getElementById("gridPreRequisitos");
  while (grid.firstChild) {
    grid.removeChild(grid.firstChild);
  }
}
