<?php

@session_start();

// Verifica se as variaves matricula e senha foram definidas

if (isset($_SESSION['matricula']) && isset($_SESSION['senha']))
    {
        $login_usuario = $_SESSION['matricula'];
    }
else // Caso nao, destroi a sessao e manda o usuario de volta para a pagina de login
    {
        echo "<script>alert('Erro!');</script>";
        header("Location:index.tpl"); //
        exit();
    }
?>
