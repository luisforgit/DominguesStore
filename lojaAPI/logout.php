<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start(); // Inicia a sessão 
}
session_unset(); // Limpa todas as variáveis de sessão
session_destroy();
header("Location: login.php"); // Redireciona para a página de login
exit();
