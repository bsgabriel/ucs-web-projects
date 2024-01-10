<?php

include_once('dao/QuestaoDAO.php');
include_once('dao/DAO.php');
include_once('../entidades/Questao.php');
include_once('../entidades/Alternativa.php');

class PostgresQuestaoDao extends DAO implements QuestaoDao
{
    protected $table_name = 'questoes';

    public function inserir($questao)
    {
        $query = "INSERT INTO " . $this->table_name .
            " (descricao, tipo, imagem) VALUES" .
            " (:descricao, :tipo, :imagem)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":descricao", $questao->getDescricao());
        $stmt->bindParam(":tipo", $questao->getTipo());
        $stmt->bindParam(":imagem", $questao->getImagem());

        $lastInsertId = null;
        if ($stmt->execute()) {
            $lastInsertId = $this->conn->lastInsertId();
        } else {
            $lastInsertId = null;
        }

        if (!is_null($lastInsertId)) {
            //Se a questão é do tipo "múltipla escolha" ou "verdadeiro e falso" insere as alternativas
            if ($questao->getTipo() == 'M' || $questao->getTipo() == 'V') {
                $factory = new PostgresDaofactory();
                $alternativaDAO = $factory->getAlternativaDao();

                foreach ($questao->getAlternativas() as $alternativa) {
                    $alternativa->setIdQuestao($lastInsertId);
                    $alternativaDAO->inserir($alternativa);
                }
            }
        }

    }

    public function alterar($questao)
    {
        $query = "UPDATE " . $this->table_name .
            " SET descricao = :descricao, tipo = :tipo, imagem = :imagem" .
            " WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":descricao", $questao->getDescricao());
        $stmt->bindParam(":tipo", $questao->getTipo());
        $stmt->bindParam(":imagem", $questao->getImagem());
        $stmt->bindParam(':id', $questao->getId());

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

    public function remover($questao)
    {
        return $this->removerPorId($questao->getId());
    }

    public function buscarTodos()
    {
        $query = "SELECT 
                    q.id, q.descricao, q.tipo, q.imagem
                FROM
                    " . $this->table_name . " AS q
                ORDER BY
                    q.id ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $factory = new PostgresDaofactory();
        $alternativaDAO = $factory->getAlternativaDao();
        $questoes = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            //Busca as alternativas da questão através do PostgresAlternativaDao.php
            $alternativas = $alternativaDAO->buscarPorQuestao($row['id']);
            $questoes[] = new Questao($row['id'], $row['descricao'], $row['tipo'], $row['imagem'], $alternativas);
        }

        return $questoes;
    }

    public function buscarPorId($id)
    {
        $query = "SELECT 
                    q.id, q.descricao, q.tipo, q.imagem
                FROM
                    " . $this->table_name . " AS q
                WHERE
                    q.id = ?
                LIMIT
                    1 OFFSET 0";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();

        $factory = new PostgresDaofactory();
        $alternativaDAO = $factory->getAlternativaDao();
        $questao = null;

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            //Busca as alternativas da questão através do PostgresAlternativaDao.php
            $alternativas = $alternativaDAO->buscarPorQuestao($row['id']);
            $questao = new Questao($row['id'], $row['descricao'], $row['tipo'], $row['imagem'], $alternativas);
        }

        return $questao;
    }

    public function uploadImagem($questao)
    {
        $query = "UPDATE " . $this->table_name .
            " SET descricao = :descricao, tipo = :tipo, imagem = :imagem" .
            " WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":descricao", $questao->getDescricao());
        $stmt->bindParam(":tipo", $questao->getTipo());
        $stmt->bindParam(":imagem", $questao->getImagem());
        $stmt->bindParam(':id', $questao->getId());

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}
?>