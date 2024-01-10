<?php

include_once('QuestionarioDAO.php');
include_once('dao/DAO.php');
include_once('../entidades/Questionario.php');
include_once('../entidades/Elaborador.php');
include_once('../entidades/Questao.php');

class PostgresQuestionarioDao extends DAO implements QuestionarioDao
{
    protected $table_name = 'questionarios';

    public function inserir($questionario)
    {
        $query = "INSERT INTO " . $this->table_name .
            " (nome, descricao, dataCriacao, notaAprovacao, id_elaborador) VALUES" .
            " (:nome, :descricao, :dataCriacao, :notaAprovacao, :id_elaborador)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":nome", $questionario->getNome());
        $stmt->bindParam(":descricao", $questionario->getDescricao());
        $stmt->bindParam(":dataCriacao", $questionario->getDataCriacao());
        $stmt->bindParam(":notaAprovacao", $questionario->getNotaAprovacao());
        $stmt->bindParam(":id_elaborador", $questionario->getElaborador()->getId());

        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
            ;
        } else {
            return -1;
        }
    }

    public function alterar($questionario)
    {
        $query = "UPDATE " . $this->table_name .
            " SET nome = :nome, descricao = :descricao, dataCriacao = :dataCriacao, notaAprovacao = :notaAprovacao, id_elaborador = :id_elaborador" .
            " WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":nome", $questionario->getNome());
        $stmt->bindParam(":descricao", $questionario->getDescricao());
        $stmt->bindParam(":dataCriacao", $questionario->getDataCriacao());
        $stmt->bindParam(":notaAprovacao", $questionario->getNotaAprovacao());
        $stmt->bindParam(":id_elaborador", $questionario->getElaborador()->getId());
        $stmt->bindParam(':id', $questionario->getId());

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    private function removerPorId($id)
    {
        $query = "DELETE FROM " . $this->table_name .
            " WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function remover($questionario)
    {
        return $this->removerPorId($questionario->getId());
    }

    public function buscarTodos()
    {
        return $this->buscarTodosOffset(0, 0);
    }

    public function buscarPorId($id)
    {
        $query = "SELECT 
                    q.id, q.nome AS questionario_nome, q.descricao, q.dataCriacao, q.notaAprovacao, 
                    q.id_elaborador, u.login, u.senha, u.nome AS usuario_nome, u.email, u.instituicao                    
                FROM
                    " . $this->table_name . " AS q
                INNER JOIN
                    usuarios u ON u.id = q.id_elaborador
                WHERE
                    q.id = ?
                LIMIT
                    1 OFFSET 0";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();

        $factory = new PostgresDaofactory();
        $questionarioQuestaoDAO = $factory->getQuestionarioQuestaoDao();
        $questionario = null;
        $questoes = [];

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            //Busca as questões do questionário através do PostgresQuestionarioQuestaoDao.php
            $questoes = $questionarioQuestaoDAO->buscarQuestoesQuestionario($row['id']);
            $elaborador = new Elaborador($row['id_elaborador'], $row['login'], $row['senha'], $row['usuario_nome'], $row['email'], $row['instituicao']);
            $questionario = new Questionario($row['id'], $row['questionario_nome'], $row['descricao'], $row['dataCriacao'], $row['notaAprovacao'], $elaborador, $questoes);
        }

        return $questionario;
    }

    public function buscarPorNome($nome)
    {
        $query = "SELECT 
                    q.id, q.nome AS questionario_nome, q.descricao, q.dataCriacao, q.notaAprovacao, 
                    q.id_elaborador, u.login, u.senha, u.nome AS usuario_nome, u.email, u.instituicao
                FROM
                    " . $this->table_name . " AS q
                INNER JOIN
                    usuarios u ON u.id = q.id_elaborador
                WHERE
                    q.nome like %?%";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $nome);
        $stmt->execute();

        $factory = new PostgresDaofactory();
        $questionarioQuestaoDAO = $factory->getQuestionarioQuestaoDao();
        $questionarios = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            //Busca as questões do questionário através do PostgresQuestionarioQuestaoDao.php
            $questoes = $questionarioQuestaoDAO->buscarQuestoesQuestionario($row['id']);
            $elaborador = new Elaborador($row['id_elaborador'], $row['login'], $row['senha'], $row['usuario_nome'], $row['email'], $row['instituicao']);
            $questionario = new Questionario($row['id'], $row['questionario_nome'], $row['descricao'], $row['dataCriacao'], $row['notaAprovacao'], $elaborador, $questoes);

            $questionarios[] = $questionario;
        }

        return $questionarios;
    }

    public function buscarPorElaborador($elaborador)
    {
        $query = "SELECT 
                    q.id, q.nome AS questionario_nome, q.descricao, q.dataCriacao, q.notaAprovacao                   
                FROM
                    " . $this->table_name . " AS q
                WHERE
                    q.id_elaborador = ?
                ORDER BY
                    q.id ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $elaborador->getId());
        $stmt->execute();

        $factory = new PostgresDaofactory();
        $questionarioQuestaoDAO = $factory->getQuestionarioQuestaoDao();
        $questionarios = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            //Busca as questões do questionário através do PostgresQuestionarioQuestaoDao.php
            $questoes = $questionarioQuestaoDAO->buscarQuestoesQuestionario($row['id']);
            $questionario = new Questionario($row['id'], $row['questionario_nome'], $row['descricao'], $row['dataCriacao'], $row['notaAprovacao'], $elaborador, $questoes);

            $questionarios[] = $questionario;
        }

        return $questionarios;
    }

    public function buscarTodosOffset(int $start, int $limit)
    {
        $query = "SELECT 
        q.id, q.nome AS questionario_nome, q.descricao, q.dataCriacao, q.notaAprovacao,
        q.id_elaborador, u.login, u.senha, u.nome AS usuario_nome, u.email, u.instituicao
    FROM
        " . $this->table_name . " AS q
    INNER JOIN
        usuarios u ON u.id = q.id_elaborador
    ORDER BY
        q.id ASC";

        if ($limit > 0) {
            $query = $query . " "
                . "offset " . $start . " "
                . "limit " . $limit;
        }

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $factory = new PostgresDaofactory();
        $questionarioQuestaoDAO = $factory->getQuestionarioQuestaoDao();
        $questionarios = [];
        $questoes = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            //Busca as questões do questionário através do PostgresQuestionarioQuestaoDao.php
            $questoes = $questionarioQuestaoDAO->buscarQuestoesQuestionario($row['id']);
            $elaborador = new Elaborador($row['id_elaborador'], $row['login'], $row['senha'], $row['usuario_nome'], $row['email'], $row['instituicao']);
            $questionario = new Questionario($row['id'], $row['questionario_nome'], $row['descricao'], $row['datacriacao'], $row['notaaprovacao'], $elaborador, $questoes);

            $questionarios[] = $questionario;
        }

        return $questionarios;
    }
}
?>