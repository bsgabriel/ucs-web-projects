<?php
include_once('UsuarioDao.php');
include_once('dao/DAO.php');
include_once('../entidades/Elaborador.php');
include_once('../entidades/Respondente.php');
include_once('../entidades/Administrador.php');

class PostgresUsuarioDao extends DAO implements UsuarioDao
{
    /* colunas da tabela */
    private $table_name = 'usuarios';
    private $col_id = 'id';
    private $col_login = 'login';
    private $col_senha = 'senha';
    private $col_nome = 'nome';
    private $col_email = 'email';
    private $col_instituicao = 'instituicao';
    private $col_telefone = 'telefone';
    private $col_tipo = 'tipo';

    /* parâmetros usados em statements */
    private $param_id = ':id';
    private $param_login = ':login';
    private $param_senha = ':senha';
    private $param_nome = ':nome';
    private $param_email = ':email';
    private $param_instituicao = ':instituicao';
    private $param_telefone = ':telefone';
    private $param_tipo = ':tipo';

    public function buscarTodos()
    {
        $query = "select * from " . $this->table_name . " order by " . $this->col_login;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $usuarios = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $id = $row[$this->col_id];
            $login = $row[$this->col_login];
            $senha = $row[$this->col_senha];
            $nome = $row[$this->col_nome];
            $email = $row[$this->col_email];
            $instituicao = $row[$this->col_instituicao];
            $telefone = $row[$this->col_telefone];
            $tipo = $row[$this->col_tipo];

            if ($tipo == 'E') {
                $usuarios[] = new Elaborador($id, $login, $senha, $nome, $email, $instituicao);
            } else if ($tipo == 'R') {
                $usuarios[] = new Respondente($id, $login, $senha, $nome, $email, $telefone);
            }
            // else if ($tipo == 'A') { $usuarios[] = new Administrador($id, $login, $senha, $nome, $email); } descomentar e identar caso vá usar
        }
        return $usuarios;
    }

    public function buscarElaboradores()
    {
        return $this->buscarElaboradoresOffset(0, 0);
    }

    public function buscarRespondentes()
    {
        return $this->buscarRespondentesOffset(0, 0);
    }

    public function inserir($usuario)
    {
        $query = "insert into " . $this->table_name . " ("
            . $this->col_login . ", "
            . $this->col_senha . ", "
            . $this->col_nome . ", "
            . $this->col_email . ", "
            . $this->col_tipo . ", "
            . $this->col_instituicao . ", "
            . $this->col_telefone . ") "
            . "values ("
            . $this->param_login . ", "
            . $this->param_senha . ", "
            . $this->param_nome . ", "
            . $this->param_email . ", "
            . $this->param_tipo . ", "
            . $this->param_instituicao . ", "
            . $this->param_telefone . ")";

        $instituicao = null;
        $telefone = null;

        if ($usuario instanceof Elaborador)
            $instituicao = $usuario->getInstituicao();
        else if ($usuario instanceof Respondente)
            $telefone = $usuario->getTelefone();

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam($this->param_login, $usuario->getLogin());
        $stmt->bindParam($this->param_senha, $usuario->getSenha());
        $stmt->bindParam($this->param_nome, $usuario->getNome());
        $stmt->bindParam($this->param_email, $usuario->getEmail());
        $stmt->bindParam($this->param_tipo, $usuario->getTipo());
        $stmt->bindParam($this->param_instituicao, $instituicao);
        $stmt->bindParam($this->param_telefone, $telefone);
        $stmt->execute();
    }

    public function alterar($usuario)
    {
        $query = "update " . $this->table_name . " set "
            . $this->col_login . " = " . $this->param_login . ", "
            . $this->col_senha . " = " . $this->param_senha . ", "
            . $this->col_nome . " = " . $this->param_nome . ", "
            . $this->col_email . " = " . $this->param_email . ", "
            . $this->col_tipo . " = " . $this->param_tipo . ", "
            . $this->col_instituicao . " = " . $this->param_instituicao . ", "
            . $this->col_telefone . " = " . $this->param_telefone . " "
            . "where " . $this->col_id . " = " . $this->param_id;

        $instituicao = null;
        $telefone = null;

        if ($usuario instanceof Elaborador)
            $instituicao = $usuario->getInstituicao();
        else if ($usuario instanceof Respondente)
            $telefone = $usuario->getTelefone();

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam($this->param_login, $usuario->getLogin());
        $stmt->bindParam($this->param_senha, $usuario->getSenha());
        $stmt->bindParam($this->param_nome, $usuario->getNome());
        $stmt->bindParam($this->param_email, $usuario->getEmail());
        $stmt->bindParam($this->param_tipo, $usuario->getTipo());
        $stmt->bindParam($this->param_instituicao, $instituicao);
        $stmt->bindParam($this->param_telefone, $telefone);
        $stmt->bindParam($this->param_id, $usuario->getId());
        $stmt->execute();
    }

    public function removerPorId($id)
    {
        $query = "delete from " . $this->table_name
            . " where " . $this->col_id . " = " . $this->param_id;

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam($this->param_id, $id);
        $stmt->execute();
    }

    public function remover($usuario)
    {
        $this->removerPorId($usuario->getId());
    }

    public function buscarPorId($id)
    {
        $query = "select * from " . $this->table_name
            . " where " . $this->col_id . " = " . $this->param_id;

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam($this->param_id, $id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row)
            return null;

        $id = $row[$this->col_id];
        $login = $row[$this->col_login];
        $senha = $row[$this->col_senha];
        $nome = $row[$this->col_nome];
        $email = $row[$this->col_email];
        $instituicao = $row[$this->col_instituicao];
        $telefone = $row[$this->col_telefone];
        $tipo = $row[$this->col_tipo];

        $usuario = null;
        if ($tipo == 'E') {
            $usuario = new Elaborador($id, $login, $senha, $nome, $email, $instituicao);
        } else if ($tipo == 'R') {
            $usuario = new Respondente($id, $login, $senha, $nome, $email, $telefone);
        } else if ($tipo == 'A') {
            $usuario = new Administrador($id, $login, $senha, $nome, $email);
        }
        return $usuario;
    }

    public function buscarPorLogin($login)
    {
        $query = "select * from " . $this->table_name
            . " where " . $this->col_login . " = " . $this->param_login;

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam($this->param_login, $login);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row)
            return null;

        $id = $row[$this->col_id];
        $login = $row[$this->col_login];
        $senha = $row[$this->col_senha];
        $nome = $row[$this->col_nome];
        $email = $row[$this->col_email];
        $instituicao = $row[$this->col_instituicao];
        $telefone = $row[$this->col_telefone];
        $tipo = $row[$this->col_tipo];

        $usuario = null;
        if ($tipo == 'E') {
            $usuario = new Elaborador($id, $login, $senha, $nome, $email, $instituicao);
        } else if ($tipo == 'R') {
            $usuario = new Respondente($id, $login, $senha, $nome, $email, $telefone);
        } else if ($tipo == 'A') {
            $usuario = new Administrador($id, $login, $senha, $nome, $email);
        }
        return $usuario;
    }

    public function buscarPorNomeEmail($pesquisa, $tipo)
    {
        $pesquisa = "'%" . $pesquisa . "%'";

        $query = "select * from " . $this->table_name
            . " where (" . $this->col_nome . " like " . $pesquisa
            . " or " . $this->col_email . " like " . $pesquisa . ")";

        if (!is_null($tipo)) {
            $query = $query . " and " . $this->col_tipo . " = " . $this->param_tipo;
        }

        $query = $query . " order by " . $this->col_login;

        $stmt = $this->conn->prepare($query);

        if (!is_null($tipo)) {
            $stmt->bindParam($this->param_tipo, $tipo);
        }

        $stmt->execute();

        $usuarios = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $id = $row[$this->col_id];
            $login = $row[$this->col_login];
            $senha = $row[$this->col_senha];
            $nome = $row[$this->col_nome];
            $email = $row[$this->col_email];
            $instituicao = $row[$this->col_instituicao];
            $telefone = $row[$this->col_telefone];
            $tipo = $row[$this->col_tipo];

            if ($tipo == 'E') {
                $usuarios[] = new Elaborador($id, $login, $senha, $nome, $email, $instituicao);
            } else if ($tipo == 'R') {
                $usuarios[] = new Respondente($id, $login, $senha, $nome, $email, $telefone);
            }
        }

        return $usuarios;
    }

    public function buscarElaboradoresOffset(int $start, int $limit)
    {
        $query = "select * from " . $this->table_name
            . " where " . $this->col_tipo . " = 'E'"
            . " order by " . $this->col_login;

        if ($limit > 0) {
            $query = $query . " "
                . "offset " . $start . " "
                . "limit " . $limit;
        }
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $elaboradores = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $id = $row[$this->col_id];
            $login = $row[$this->col_login];
            $senha = $row[$this->col_senha];
            $nome = $row[$this->col_nome];
            $email = $row[$this->col_email];
            $instituicao = $row[$this->col_instituicao];
            $elaboradores[] = new Elaborador($id, $login, $senha, $nome, $email, $instituicao);
        }
        return $elaboradores;
    }

    public function buscarRespondentesOffset(int $start, int $limit)
    {
        $query = "select * from " . $this->table_name
            . " where " . $this->col_tipo . " = 'R'"
            . " order by " . $this->col_login;

        if ($limit > 0) {
            $query = $query . " "
                . "offset " . $start . " "
                . "limit " . $limit;
        }

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $respondentes = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $id = $row[$this->col_id];
            $login = $row[$this->col_login];
            $senha = $row[$this->col_senha];
            $nome = $row[$this->col_nome];
            $email = $row[$this->col_email];
            $telefone = $row[$this->col_telefone];
            $respondentes[] = new Respondente($id, $login, $senha, $nome, $email, $telefone);
        }
        return $respondentes;
    }
}

?>