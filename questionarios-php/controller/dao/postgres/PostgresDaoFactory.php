<?php
include_once(__DIR__ . '../DaoFactory.php');
include_once(__DIR__ . '/PostgresUsuarioDao.php');
include_once(__DIR__ . '/PostgresAlternativaDao.php');
include_once(__DIR__ . '/PostgresQuestaoDao.php');
include_once(__DIR__ . '/PostgresQuestionarioDao.php');
include_once(__DIR__ . '/PostgresQuestionarioQuestaoDao.php');
include_once(__DIR__ . '/PostgresOfertaDao.php');

class PostgresDaoFactory extends DaoFactory
{
    private $host = "localhost";
    private $db_name = "questionarios";
    private $port = "5432";
    private $username = "postgres";
    private $password = "postgres";
    public $conn;

    public function getConnection()
    {
        $this->conn = null;
        try {
            $this->conn = new PDO("pgsql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->db_name, $this->username, $this->password);
        } catch (PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }

    public function getUsuarioDao()
    {
        return new PostgresUsuarioDao($this->getConnection());
    }

    public function getQuestaoDao()
    {
        return new PostgresQuestaoDao($this->getConnection());
    }

    public function getAlternativaDao()
    {
        return new PostgresAlternativaDao($this->getConnection());
    }

    public function getQuestionarioQuestaoDao()
    {
        return new PostgresQuestionarioQuestaoDao($this->getConnection());
    }

    public function getQuestionarioDao()
    {
        return new PostgresQuestionarioDao($this->getConnection());
    }

    public function getOfertaDao()
    {
        return new PostgresOfertaDao($this->getConnection());
    }

}

?>