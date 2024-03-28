<?php
session_start();

include_once "Conexao.php";
include_once "Usuario.php";
include_once "Cookie.php";

$conexao = new Conexao();
$usuario = new Usuario($conexao);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Se o formulário de login foi enviado
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        if ($usuario->login($username, $password)) {
            $_SESSION['username'] = $username;
            if (isset($_POST['remember'])) {
                Cookie::set('username', $username, 86400); // cookie válido por 1 dia
            }
            header("Location: ListaTarefas.php");
            exit();
        } else {
            header("Location: index.html");
            exit();
        }
    }
}

// Verificar se o usuário está logado via cookie
if (isset($_COOKIE['username']) && !isset($_SESSION['username'])) {
    $_SESSION['username'] = $_COOKIE['username'];
}

if (!isset($_SESSION['lista_Fazer'])) {
    $_SESSION['lista_Fazer'] = array();
}

if (!isset($_SESSION['lista_doing'])) {
    $_SESSION['lista_doing'] = array();
}

if (!isset($_SESSION['lista_done'])) {
    $_SESSION['lista_done'] = array();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['item']) && $_POST['item'] !== '') {
        $item = $_POST['item'];
        $_SESSION['lista_Fazer'][] = $item;
    }

    if (isset($_POST['excluir'])) {
        if (!empty($_POST['check'])) {
            foreach ($_POST['check'] as $key => $value) {
                if (isset($_SESSION['lista_Fazer'][$key])) {
                    unset($_SESSION['lista_Fazer'][$key]);
                } elseif (isset($_SESSION['lista_doing'][$key])) {
                    unset($_SESSION['lista_doing'][$key]);
                } elseif (isset($_SESSION['lista_done'][$key])) {
                    unset($_SESSION['lista_done'][$key]);
                }
            }
        }
    }

    if (isset($_POST['limpa_lista'])) {
        unset($_SESSION['lista_Fazer']);
        unset($_SESSION['lista_doing']);
        unset($_SESSION['lista_done']);
    }

    if (isset($_POST['move_item/A_fazer1']) || isset($_POST['move_item/A_fazer2'])) {
        // Mover item para a lista desejada
        $target_list1 = $_POST['move_item/A_fazer1'];
        $target_list2 = $_POST['move_item/A_fazer2'];
        if (!empty($_POST['check'])) {
            foreach ($_POST['check'] as $key => $value) {
                $item = $_SESSION['lista_Fazer'][$key];
                unset($_SESSION['lista_Fazer'][$key]);
                if ($target_list1 == 'Mover para Fazendo') {
                    $_SESSION['lista_doing'][] = $item;
                } elseif ($target_list2 == 'Mover para Feito') {
                    $_SESSION['lista_done'][] = $item;
                }
            }
        }
    }

    if (isset($_POST['move_item/Fazendo'])) {
        // Mover item para a lista desejada
        $target_list = $_POST['move_to'];
        if (!empty($_POST['check'])) {
            foreach ($_POST['check'] as $key => $value) {
                $item = $_SESSION['lista_doing'][$key];
                unset($_SESSION['lista_doing'][$key]);
                if ($target_list == 'done') {
                    $_SESSION['lista_done'][] = $item;
                }
            }
        }
    }

    header("Location: {$_SERVER['PHP_SELF']}");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Make a task</title>
    <link rel="icon" href="img/barra-icon.png" type="image/x-icon">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <h1 id="nova-tarefa">Nova tarefa</h1>
    <div id="adicionar_item">
        <form action="ListaTarefas.php" method="post" id="adiconar">
            <input type="text" name="item" placeholder="Adicionar tarefa...">
            <input type="submit" value="Adicionar à lista" id="botao-add">
        </form>
    </div>
    <div id="botao_limpar">
        <form action="ListaTarefas.php" method="post">
            <input type="submit" name="limpa_lista" value="Limpar toda a lista">
        </form>
    </div>
    <center>
        <div class="board">
            <div id="column1" class="column">
                <h2>Tarefas</h2>
                <form action="ListaTarefas.php" method="post">
                    <ul>
                        <?php
                        if (!empty($_SESSION['lista_Fazer'])) {
                            foreach ($_SESSION['lista_Fazer'] as $key => $item) {
                                echo "<li>";
                                echo "<input type='checkbox' name='check[$key]' value='1'>";
                                echo "<label>$item</label>";
                                echo "</li>";
                            }
                        } else {
                            echo "<li>Nenhum item na lista.</li>";
                        }
                        ?>
                    </ul>
                    <input type="hidden" name="move_to1" value="doing">
                    <input type="submit" name="move_item/A_fazer1" value="Mover para Fazendo">
                    <input type="hidden" name="move_to2" value="done">
                    <input type="submit" name="move_item/A_fazer2" value="Mover para Feito">

                    <input type="submit" name="excluir" value="Excluir Itens Marcados">
                </form>
            </div>
            <div id="column2" class="column">
                <h2 id="titulo-3">Fazendo</h2>
                <form action="ListaTarefas.php" method="post">
                    <ul>
                        <?php
                        if (!empty($_SESSION['lista_doing'])) {
                            foreach ($_SESSION['lista_doing'] as $key => $item) {
                                echo "<li>";
                                echo "<input type='checkbox' name='check[$key]' value='1'>";
                                echo "<label>$item</label>";
                                echo "</li>";
                            }
                        } else {
                            echo "<li>Nenhum item na lista.</li>";
                        }
                        ?>
                    </ul>


                    <input type="hidden" name="move_to" value="done">
                    <input type="submit" name="move_item/Fazendo" value="Mover para Feito">

                    <input type="submit" name="excluir" value="Excluir Itens Marcados">
                </form>
            </div>
            <div id="column3" class="column">
                <div class="titulo">
                    <h2>Feito</h2>
                </div>
                <form action="ListaTarefas.php" method="post">
                    <ul>
                        <?php
                        if (!empty($_SESSION['lista_done'])) {
                            foreach ($_SESSION['lista_done'] as $key => $item) {
                                echo "<li>";
                                echo "<input type='checkbox' name='check[$key]' value='1'>";
                                echo "<label>$item</label>";
                                echo "</li>";
                            }
                        } else {
                            echo "<li>Nenhum item na lista.</li>";
                        }
                        ?>
                    </ul>
                    <input type="submit" name="excluir" value="Excluir Itens Marcados">
                </form>
            </div>
        </div>
    </center>
</body>

</html>
<style>
    body {
        background-color: #ffeebc;
    }

    #nova-tarefa {
        text-align: center;
        font-family: Arial, Helvetica, sans-serif;
        font-weight: 700px;
        margin: 30px;
    }

    #adicionar_item {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    input[type="text"] {
        background: none;
        border: none;
        border-bottom: 1px solid black;
        outline: none;
        color: black;
        font-size: 20px;
        letter-spacing: 2px;
    }

    input[type="submit"] {
        background: black;
        border: none;
        color: #fff;
        padding: 10px 20px;
        border-radius: 15px;
    }

    #botao_limpar {
        margin-top: 20px;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    ul {
        list-style: none;
        text-align: center;

    }

    h2 {
        text-align: center;
        font-family: Arial, Helvetica, sans-serif;
        font-weight: 700px;
        margin: 30px;
        display: flex;
    }

    style>.board {
        display: flex;
        justify-content: space-around;
        align-items: flex-start;
        width: 100%;

    }

    .column {
        width: 30%;
        background-color: #e2e3e7;
        padding: 40px;
        margin-bottom: 20px;
        border-radius: 20px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    h2 {
        text-align: center;
    }

    ul {
        list-style-type: none;
        padding: 0;
    }

    li {
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: -10%;
    }

    input[type="submit"] {
        margin: 10px;
    }

input[type='checkbox'] {
    margin-right: 5px; 
    appearance: none;
    width: 18px; 
    height: 18px; 
    border: 2px solid black;
    background-color: transparent;
    border-radius: 3px; 
    position: relative; 
}

input[type='checkbox']:checked {
    background-color: black; 
}


input[type='checkbox']:checked::before {
    content: ''; 
    position: absolute; 
    top: 50%; 
    left: 50%;
    transform: translate(-50%, -50%); 
    width: 10px; 
    height: 10px; 
    background-color: black; 
}

</style>