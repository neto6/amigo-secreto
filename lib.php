<?php

session_start();

class DB {
    public $servername;
    public $username;
    public $password;
    public $dbname;
    public $conn;

    public function __construct() {
        $this->servername = 'sql130.main-hosting.eu';
        $this->username = 'u803593275_amigosecreto';
        $this->password = getenv('dbpass');
        $this->dbname = 'u803593275_amigosecreto';
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
    }

    public function insert($sql) {
        if (!$this->conn -> query($sql)){echo "Error: " . $sql . "<br>" . $this->conn->error;};
    }

    public function select($sql) {
        return $this->conn -> query($sql);
    }

    public function delete($sql) {
        $this->conn -> query($sql);
    }

    public function update($sql) {
        $this->conn -> query($sql);
    }
}

class Usuario {
    public $id;
    public $nome;
    public $email;
    public $senha;
    public $bio;
    public $foto;
    public $camiseta;
    public $calca;
    public $sapatos;

    public $db;

    public function __construct() {
        $this -> db = new DB();
    }

    public function tryLogin($try_email, $try_senha) {
        $result = $this -> db -> select("SELECT id FROM usuarios WHERE email = '$try_email' AND senha = '$try_senha'");

        if ($result->num_rows > 0) {
            return $result->fetch_assoc()['id'];
        }
    }

    public function addUsuario() {
        $this -> db -> insert("INSERT INTO usuarios (nome, email, senha, bio, foto, camiseta, calca, sapatos) 
                            VALUES ('$this->nome', '$this->email', '$this->senha', '$this->bio', 
                            '$this->foto', '$this->camiseta', '$this->calca', '$this->sapatos')");

        $this -> id = $this -> db -> conn -> insert_id;
    }

    public function getUsuario($id) {
        $result = $this -> db -> select("SELECT * FROM usuarios WHERE id = '$id'");
        
        $row = $result->fetch_assoc();

        $this -> id = $id;
        $this -> nome = $row['nome'];
        $this -> email = $row['email'];
        $this -> senha = $row['senha'];
        $this -> bio = $row['bio'];
        $this -> foto = $row['foto'];
        $this -> camiseta = $row['camiseta'];
        $this -> calca = $row['calca'];
        $this -> sapatos = $row['sapatos'];
    }

    public function getUsuarioGrupos() {
        $usuario_grupos = array();
        $result = $this -> db -> select("SELECT * FROM usuarios_grupos WHERE usuario_id = '$this->id'");
        
        while ($row = $result -> fetch_assoc()) {
            $grupo = new Grupo();
            $grupo -> getGrupo($row['grupo_id']);
            $usuario_grupos[] = $grupo;
        }

        return $usuario_grupos;
    }
}

class Grupo {
    public $id;
    public $nome;
    public $data_sorteio;
    public $valor_medio;
    public $sorteio_realizado;

    public $db;

    public function __construct() {
        $this -> db = new DB();
    }

    public function addGrupo() {
        $this -> db -> insert("INSERT INTO grupos (nome, data_sorteio, valor_medio) 
                            VALUES ('$this->nome', '$this->data_sorteio', '$this->valor_medio')");

        $this -> id = $this -> db -> conn -> insert_id;
    }

    public function getGrupo($id) {
        $result = $this -> db -> select("SELECT * FROM grupos WHERE id = '$id'");
        
        $row = $result -> fetch_assoc();

        $this -> id = $id;
        $this -> nome = $row['nome'];
        $this -> data_sorteio = $row['data_sorteio'];
        $this -> valor_medio = $row['valor_medio'];
        $this -> sorteio_realizado = $row['sorteio_realizado'];
    }

    public function addUsuario($usuario_id) {
        $this -> db -> insert("INSERT INTO usuarios_grupos (usuario_id, grupo_id) 
                            VALUES ('$usuario_id', '$this->id')");
    }

    public function getGrupoUsuarios() {
        $grupo_usuarios = array();
        $result = $this -> db -> select("SELECT * FROM usuarios_grupos WHERE grupo_id = '$this->id'");
        
        while ($row = $result -> fetch_assoc()) {
            $usuario = new Usuario();
            $usuario -> getUsuario($row['usuario_id']);
            $grupo_usuarios[] = $usuario;
        }

        return $grupo_usuarios;
    }

    public function addModerador($usuario_id) {
        $this -> db -> insert("INSERT INTO grupos_moderadores (usuario_id, grupo_id) 
                            VALUES ('$usuario_id', '$this->id')");
    }

    public function getGrupoModeradores() {
        $grupo_moderadores = array();
        $result = $this -> db -> select("SELECT * FROM grupos_moderadores WHERE grupo_id = '$this->id'");
        
        while ($row = $result -> fetch_assoc()) {
            $usuario = new Usuario();
            $usuario -> getUsuario($row['usuario_id']);
            $grupo_moderadores[] = $usuario;
        }

        return $grupo_moderadores;
    }

    public function addSolicitacao($usuario_id) {
        $this -> db -> insert("INSERT INTO grupos_solicitacoes (usuario_id, grupo_id) 
                            VALUES ('$usuario_id', '$this->id')");
    }

    public function getGrupoSolicitacoes() {
        $grupo_solicitacoes = array();
        $result = $this -> db -> select("SELECT * FROM grupos_solicitacoes WHERE grupo_id = '$this->id'");
        
        while ($row = $result -> fetch_assoc()) {
            $usuario = new Usuario();
            $usuario -> getUsuario($row['usuario_id']);
            $grupo_solicitacoes[] = $usuario;
        }

        return $grupo_solicitacoes;
    }

    public function acceptSolicitacao($usuario_id) {
        $this -> db -> delete("DELETE FROM grupos_solicitacoes WHERE grupo_id = '$this->id' AND usuario_id = '$usuario_id'");

        $this -> addUsuario($usuario_id);
    }

    public function rejectSolicitacao($usuario_id) {
        $this -> db -> delete("DELETE FROM grupos_solicitacoes WHERE grupo_id = '$this->id' AND usuario_id = '$usuario_id'");
    }

    public function addUsuarioAmigo($usuario_id, $amigo_id) {
        $this -> db -> insert("INSERT INTO usuarios_grupos_amigos (amigo_id, usuario_id, grupo_id) 
                            VALUES ('$amigo_id', '$usuario_id', '$this->id')");
    }

    public function getUsuarioAmigo($usuario_id) {
        $result = $this -> db -> select("SELECT * FROM usuarios_grupos_amigos 
        WHERE usuario_id = '$usuario_id' AND grupo_id = '$this->id'");

        $row = $result -> fetch_assoc();

        $amigo = new Usuario();
        $amigo -> getUsuario($row['amigo_id']);
        return $amigo;
    }

    public function doSorteio() {
        $usuarios = $this -> getGrupoUsuarios();
        $amigos = $this -> getGrupoUsuarios();

        shuffle($amigos);

        foreach ($usuarios as $key => $usuario) {
            $this -> addUsuarioAmigo($usuario->id, $amigos[$key]->id);
        }

        $this -> db -> insert("UPDATE grupos SET sorteio_realizado = '1' WHERE id = '$this->id'");
    }
}

?>
