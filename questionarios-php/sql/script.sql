create table usuarios (
    id serial not null,
    login varchar(20) not null unique,
    senha varchar(300) not null,
    nome varchar(150) not null,
    email varchar(150) not null,
    instituicao varchar(250),
    telefone varchar(15),
    tipo char(1) not null,
    PRIMARY KEY (id)
);

create table questionarios (
    id serial not null, 
    nome varchar(250) not null,
    descricao text not null,
    dataCriacao date not null,
    notaAprovacao numeric not null,
    id_elaborador int not null,
    PRIMARY KEY (id),
    FOREIGN KEY (id_elaborador) REFERENCES usuarios (id)
);

create table questoes (
    id serial not null,
    descricao text not null,
    tipo char(1) not null,
    imagem varchar(1000),
    PRIMARY KEY (id) 
);

create table questionario_questao(
    id serial not null,
    pontos numeric not null,
    ordem int not null,
    id_questionario int not null,
    id_questao int not null,
    PRIMARY KEY (id),
    FOREIGN KEY (id_questionario) REFERENCES questionarios (id),
    FOREIGN KEY (id_questao) REFERENCES questoes (id)
);

create table alternativas (
    id serial not null,
    descricao text not null,
    correta boolean not null,
    id_questao int not null,
    PRIMARY KEY (id),
    FOREIGN KEY (id_questao) REFERENCES questoes (id)
);

create table ofertas(
    id serial not null,
    data_oferta date not null,
    id_questionario int not null,
    id_respondente int not null,
    PRIMARY KEY (id),
    FOREIGN KEY (id_questionario) REFERENCES questionarios (id),
    FOREIGN KEY (id_respondente) REFERENCES usuarios (id)
);

create table submissoes(
   id serial not null,
   nome_ocasiao varchar(250) not null,
   descricao text not null,
   data_submissao date not null,
   id_oferta int not null,
   PRIMARY KEY (id),
   FOREIGN KEY (id_oferta) REFERENCES ofertas (id)
);

create table respostas (
    id serial not null,
    texto text,
    avaliacao numeric not null,
    id_questao int not null,
    id_submissao int not null,
    id_alternativa int,
    PRIMARY KEY (id),
    FOREIGN KEY (id_questao) REFERENCES questoes (id),
    FOREIGN KEY (id_submissao) REFERENCES submissoes (id),
    FOREIGN KEY (id_alternativa) REFERENCES alternativas (id)
);