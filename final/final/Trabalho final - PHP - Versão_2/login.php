<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Conexão com o banco de dados
    include_once "conexao.php";
    // Verificação da conexão
    if ($connect->connect_error) {
        die("Erro de conexão: " . $connect->connect_error);
    }

    // Obtenha os dados do formulário
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Consulta SQL para verificar as credenciais
    $sql = "SELECT * FROM usuarios WHERE username = '$username' AND senha = '$password'";

    $result = $connect->query($sql);

    if ($result->num_rows == 1) {
        // Login bem-sucedido, inicie a sessão e redirecione para a página do menu
        $_SESSION['username'] = $username;
        header("Location: menu.php");
        exit();
    } else {
        // Login falhou, redirecione de volta para a página de login
        header("Location: index.html");
        exit();
    }

}
?>
