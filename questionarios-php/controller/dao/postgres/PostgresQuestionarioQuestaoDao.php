<?php
include_once('QuestionarioQuestaoDAO.php');
include_once('dao/DAO.php');
include_once('../entidades/Questionario.php');
include_once('../entidades/Questao.php');

class PostgresQuestionarioQuestaoDao extends DAO implements QuestionarioQuestaoDAO
{
    protected $table_name = 'questionario_questao';

    public function inserir($questionarioQuestao)
    {
        $query = "INSERT INTO " . $this->table_name .
            " (pontos, ordem, id_questionario, id_questao) VALUES" .
            " (:pontos, :ordem, :id_questionario, :id_questao)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":pontos", $questionarioQuestao->getPontos());
        $stmt->bindParam(":ordem", $questionarioQuestao->getOrdem());
        $stmt->bindParam(":id_questionario", $questionarioQuestao->getIdQuestionario());
        $stmt->bindParam(":id_questao", $questionarioQuestao->getIdQuestao());

        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        } else {
            return -1;
        }
    }

    public function alterar($questionarioQuestao)
    {
        $query = "UPDATE " . $this->table_name .
            " SET pontos = :pontos, ordem = :ordem, id_questionario = :id_questionario, id_questao = :id_questao" .
            " WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":pontos", $questionarioQuestao->getPontos());
        $stmt->bindParam(":ordem", $questionarioQuestao->getOrdem());
        $stmt->bindParam(":id_questionario", $questionarioQuestao->getIdQuestionario());
        $stmt->bindParam(":id_questao", $questionarioQuestao->getIdQuestao());
        $stmt->bindParam(':id', $questionarioQuestao->getId());

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function remover($questionarioQuestao)
    {
        return $this->removerPorId($questionarioQuestao->getId());
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

    public function removerTodasQuestoesDoQuestionario($id_questionario)
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

    public function buscarPorId($id)
    {
        $query = "SELECT 
                    qq.id, qq.pontos, qq.ordem, qq.id_questionario, qq.id_questao 
                FROM
                    " . $this->table_name . " AS qq
                WHERE
                    qq.id = ?
                LIMIT
                    1 OFFSET 0";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();

        $questionarioQuestao = null;

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $questionarioQuestao = new QuestionarioQuestao($row['id'], $row['pontos'], $row['ordem'], $row['id_questionario'], $row['id_questao']);
        }

        return $questionarioQuestao;
    }

    public function buscarQuestoesQuestionario($id_questionario)
    {
        $query = "SELECT
                    qq.id_questao
                FROM
                    " . $this->table_name . " AS qq
                WHERE
                    qq.id_questionario = ?
                ORDER BY
                    qq.ordem ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id_questionario);
        $stmt->execute();

        $factory = new PostgresDaofactory();
        $questaoDAO = $factory->getQuestaoDao();
        $questoes = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $questoes[] = $questaoDAO->buscarPorId($row['id_questao']);
        }

        return $questoes;
    }
}
?>