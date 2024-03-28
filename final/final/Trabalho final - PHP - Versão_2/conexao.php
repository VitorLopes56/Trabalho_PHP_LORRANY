<?php

class Conexao {
    protected $server = "localhost";
    protected $user = "root";
    protected $password = "";
    protected $dbname = "sistema_login";

    public function getConnection() {
        return new mysqli($this->server, $this->user, $this->password, $this->dbname);
    }
}

$server = "localhost";
$user = "root";
$password = "";
$dbname = "sistema_login";

$connect = mysqli_connect($server,$user,$password,$dbname);
