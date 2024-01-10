<?php

include_once('dao/AlternativaDAO.php');
include_once('dao/DAO.php');
include_once('../entidades/Alternativa.php');

class PostgresAlternativaDao extends DAO implements AlternativaDao
{
    protected $table_name = 'alternativas';

    public function inserir($alternativa)
    {
        $query = "INSERT INTO " . $this->table_name .
            " (descricao, correta, id_questao) VALUES" .
            " (:descricao, :correta, :idQuestao)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":descricao", $alternativa->getDescricao());
        $stmt->bindParam(":correta", $alternativa->getCorreta());
        $stmt->bindParam(":idQuestao", $alternativa->getIdQuestao());

        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
            ;
        } else {
            return -1;
        }
    }

    public function alterar($alternativa)
    {
        $query = "UPDATE " . $this->table_name .
            " SET descricao = :descricao, correta = :correta, id_questao = :idQuestao" .
            " WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":descricao", $alternativa->getDescricao());
        $stmt->bindParam(":correta", $alternativa->getCorreta());
        $stmt->bindParam(":idQuestao", $alternativa->getIdQuestao());
        $stmt->bindParam(':id', $alternativa->getId());

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

    public function remover($alternativa)
    {
        return $this->removerPorId($alternativa->getId());
    }

    public function removerPorQuestao($idQuestao)
    {
        $query = "DELETE FROM " . $this->table_name .
            " WHERE id_questao = :idQuestao";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':idQuestao', $idQuestao);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function buscarTodos()
    {
        $query = "SELECT 
                    a.id, a.descricao, a.correta, a.id_questao
                FROM
                    " . $this->table_name . " AS a
                ORDER BY
                    a.id ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $alternativas = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $alternativas[] = new Alternativa($row['id'], $row['descricao'], $row['correta'], $row['id_questao']);
        }

        return $alternativas;
    }

    public function buscarPorId($id)
    {
        $query = "SELECT 
                    a.id, a.descricao, a.correta, a.id_questao
                FROM
                    " . $this->table_name . " AS a
                WHERE
                    a.id = ?
                LIMIT
                    1 OFFSET 0";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();

        $alternativa = null;

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $alternativa = new Alternativa($row['id'], $row['descricao'], $row['correta'], $row['id_questao']);
        }

        return $alternativa;
    }

    public function buscarPorQuestao($idQuestao)
    {
        $query = "SELECT 
                    a.id, a.descricao, a.correta, a.id_questao
                FROM
                    " . $this->table_name . " AS a
                WHERE
                    a.id_questao = ?
                ORDER BY
                    a.id ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $idQuestao);
        $stmt->execute();

        $alternativas = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $alternativas[] = new Alternativa($row['id'], $row['descricao'], $row['correta'], $row['id_questao']);
        }

        return $alternativas;
    }
}
?>