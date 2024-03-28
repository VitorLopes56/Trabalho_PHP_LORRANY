<?php

include_once "Conexao.php";

class Usuario {
    private $conexao;

    public function __construct($conexao) {
        $this->conexao = $conexao;
    }

    public function login($username, $password) {
        $sql = "SELECT * FROM usuarios WHERE username = '$username' AND senha = '$password'";
        $result = $this->conexao->getConnection()->query($sql);
        if ($result->num_rows == 1) {
            return true;
        } else {
            return false;
        }
    }

    public function registrar($username, $password) {
        $sql = "INSERT INTO usuarios (username, senha) VALUES ('$username', '$password')";
        $query = $this->conexao->getConnection()->query($sql);
        return $query ? true : false;
    }

    public function usuarioExiste($username) {
        $check_query = "SELECT * FROM usuarios WHERE username = '$username'";
        $check_result = $this->conexao->getConnection()->query($check_query);
        return $check_result->num_rows > 0 ? true : false;
    }
}
?>

