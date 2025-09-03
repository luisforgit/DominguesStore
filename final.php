<?php

require_once 'auth.php';
require_once 'db.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

// Limpa o carrinho do utilizador após a compra
$sql = $con->prepare("DELETE FROM carrinho WHERE userId = ?");
$userId = $_SESSION["user_id"];
$sql->bind_param("i", $userId);
$sql->execute();
if ($sql->affected_rows > 0) {
    // Carrinho limpo com sucesso
} else {
    // Erro ao limpar o carrinho
    echo "Erro ao limpar o carrinho.";
}
$sql->close();
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <title>Obrigado!</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light d-flex flex-column justify-content-center align-items-center vh-100">
    <div class="card shadow p-4" style="max-width: 400px;">
        <h1 class="mb-4 text-center">Obrigado pela sua compra!</h1>
        <form action="index.php" class="text-center">
            <button type="submit" class="btn btn-primary">Voltar à Loja</button>
        </form>
    </div>
</body>

</html>