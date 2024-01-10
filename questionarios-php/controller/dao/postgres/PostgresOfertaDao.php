<?php

include_once('OfertaDAO.php');
include_once('dao/DAO.php');
include_once('../entidades/Oferta.php');
include_once('../entidades/Questionario.php');
include_once('../entidades/Respondente.php');
include_once('../entidades/Elaborador.php');

class PostgresOfertaDao extends DAO implements OfertaDAO
{
    protected $table_name = 'ofertas';

    public function inserir($oferta)
    {
        $query = "INSERT INTO " . $this->table_name .
            " (data_oferta, id_questionario, id_respondente) VALUES" .
            " (:data_oferta, :id_questionario, :id_respondente)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":data_oferta", $oferta->getDataOferta());
        $stmt->bindParam(":id_questionario", $oferta->getQuestionario()->getId());
        $stmt->bindParam(":id_respondente", $oferta->getRespondente()->getId());

        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        } else {
            return -1;
        }
    }

    public function alterar($oferta)
    {
        $query = "UPDATE " . $this->table_name .
            " SET data_oferta = :data_oferta, id_questionario = :id_questionario, id_respondente = :id_respondente" .
            " WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":data_oferta", $oferta->getDataOferta());
        $stmt->bindParam(":id_questionario", $oferta->getQuestionario()->getId());
        $stmt->bindParam(":id_respondente", $oferta->getRespondente()->getId());
        $stmt->bindParam(':id', $oferta->getId());

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function remover($oferta)
    {
        $query = "DELETE FROM " . $this->table_name .
            " WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":id", $oferta->getId());

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function removerOfertasQuestionario($id_questionario)
    {
        $query = "DELETE FROM " . $this->table_name .
            " WHERE id_questionario = :id_questionario";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":id_questionario", $id_questionario);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function removerOfertasRespondente($id_respondente)
    {
        $query = "DELETE FROM " . $this->table_name .
            " WHERE id_respondente = :id_respondente";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":id_respondente", $id_respondente);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function buscarPorId($id)
    {
        $query = "SELECT 
                    o.id, o.data_oferta, o.id_questionario, o.id_respondente, q.nome as questionario_nome, q.descricao, q.dataCriacao, 
                    q.notaAprovacao, q.id_elaborador
                FROM
                    " . $this->table_name . " AS o
                INNER JOIN
                    questionario q ON q.id = o.id_questionario                
                WHERE
                    o.id = ?
                LIMIT
                    1 OFFSET 0";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();

        $factory = new PostgresDaofactory();
        $questionarioQuestaoDAO = $factory->getQuestionarioQuestaoDao();

        $questoes = [];
        $oferta = null;

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            //Busca as questões do questionário através do PostgresQuestionarioQuestaoDao.php
            $questoes = $questionarioQuestaoDAO->buscarQuestoesQuestionario($row['id_questionario']);
            $elaborador = $factory->getUsuarioDao()->buscarPorId($row['id_elaborador']);
            $respondente = $factory->getUsuarioDao()->buscarPorId($row['id_respondente']);

            $questionario = new Questionario($row['id_questionario'], $row['questionario_nome'], $row['descricao'], $row['dataCriacao'], $row['notaAprovacao'], $elaborador, $questoes);
            $oferta = new Oferta($row['id'], $row['data_oferta'], $questionario, $respondente);
        }

        return $oferta;
    }

    public function buscarOfertasPorQuestionario($questionario)
    {
        $query = "SELECT
                    o.id, o.data_oferta, o.id_questionario, o.id_respondente, u.login, u.senha, u.nome, u.email, u.telefone
                FROM
                    " . $this->table_name . " AS o
                INNER JOIN
                    usuarios u ON u.id = o.id_respondente
                WHERE
                    o.id_questionario = ?
                ORDER BY
                    o.id ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $questionario->getId());
        $stmt->execute();

        $ofertas = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $respondente = new Respondente($row['id_respondente'], $row['login'], $row['senha'], $row['nome'], $row['email'], $row['telefone']);
            $oferta = new Oferta($row['id'], $row['data_oferta'], $questionario, $respondente);

            $ofertas[] = $oferta;
        }

        return $ofertas;
    }

    public function buscarOfertasPorRespondente(Respondente $respondente)
    {
        return $this->buscarOfertasPorRespondenteOffset($respondente, 0, 0);
    }

    public function buscarOfertasPorRespondenteOffset(Respondente $respondente, int $start, int $limit)
    {
        $query = "SELECT
        o.id, o.data_oferta, o.id_questionario, o.id_respondente, q.nome as questionario_nome, q.descricao, q.dataCriacao, 
        q.notaAprovacao, q.id_elaborador, u.login, u.senha, u.nome AS usuario_nome, u.email, u.instituicao
            FROM
                " . $this->table_name . " AS o
            INNER JOIN
                questionarios q ON q.id = o.id_questionario
            INNER JOIN
                usuarios u ON u.id = q.id_elaborador
            WHERE
                o.id_respondente = ?
            ORDER BY
                o.id ASC";

        if ($limit > 0) {
            $query = $query . " "
                . "offset " . $start . " "
                . "limit " . $limit;
        }
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $respondente->getId());
        $stmt->execute();

        $factory = new PostgresDaofactory();
        $questionarioQuestaoDAO = $factory->getQuestionarioQuestaoDao();
        $questoes = [];
        $ofertas = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            //Busca as questões do questionário através do PostgresQuestionarioQuestaoDao.php
            $questoes = $questionarioQuestaoDAO->buscarQuestoesQuestionario($row['id_questionario']);

            $elaborador = new Elaborador($row['id_elaborador'], $row['login'], $row['senha'], $row['usuario_nome'], $row['email'], $row['instituicao']);
            $questionario = new Questionario($row['id_questionario'], $row['questionario_nome'], $row['descricao'], $row['dataCriacao'], $row['notaAprovacao'], $elaborador, $questoes);
            $oferta = new Oferta($row['id'], $row['data_oferta'], $questionario, $respondente);

            $ofertas[] = $oferta;
        }

        return $ofertas;
    }
}
?>