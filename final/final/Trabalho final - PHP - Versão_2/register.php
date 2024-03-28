<?php
include_once "conexao.php";


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificação da conexão
    if ($connect->connect_error) {
        die("Erro de conexão: " . $connect->connect_error);
    }

    // Obtenha os dados do formulário
    $new_username = $_POST['new_username'];
    $new_password = $_POST['new_password'];

    // Verifique se os campos não estão vazios
    if (!empty($new_username) && !empty($new_password)) {
        // Consulta SQL para verificar se o usuário já existe
        $check_query = "SELECT * FROM usuarios WHERE username = '$new_username'";
        $check_result = $connect->query($check_query);
        if ($check_result->num_rows > 0) {
            echo "Usuário já existe!";
        } else {
            // Consulta SQL para inserir o novo usuário no banco de dados
            $sql = "INSERT INTO usuarios (username, senha) VALUES ('$new_username', '$new_password')";
            $query = mysqli_query($connect, $sql);

            if ($query) {
                echo "Usuário registrado com sucesso!";
            } else {
                echo "Erro ao registrar o usuário: " . $connect->error;
            }
        }
    } else {
        echo "Todos os campos são obrigatórios!";
    }

    // Feche a conexão com o banco de dados
    $connect->close();
}
?>

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #ffeebc;
        margin-top: 25%;
        padding: 0;
        display: auto;
        Text-align: center;
        background-color: #ffeebc;
        font-size: x-large;

    }
</style>