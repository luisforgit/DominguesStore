<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Verificar se o utilizador está logado
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php"); // Redireciona para login
    exit;
}

require_once 'db.php'; // Conexão à base de dados

$sql = "SELECT * FROM produtos";
$result = $con->query($sql);
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loja Virtual</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="loja.php">Minha Loja</a>
            <div class="ms-auto">
                <?php if (isset($_SESSION['role_id']) && $_SESSION['role_id'] === 1): ?>
                    <a href="admin.php" class="btn btn-warning">🔧 Admin</a>
                <?php endif; ?>
                <a href="carrinho.php" class="btn btn-light">🛒 Carrinho</a>
                <a href="logout.php" class="btn btn-danger ms-2">🚪 Logout</a>
            </div>
        </div>
    </nav>
    <!-- Barra de Pesquisa -->
    <div class="container mt-3">
        <form class="d-flex" action="loja.php" method="GET">
            <input class="form-control me-2" type="search" name="search" placeholder="Pesquisar produtos..." aria-label="Pesquisar">
            <button class="btn btn-primary" type="submit">Buscar</button>
        </form>
    </div>
    <div class="container mt-5">
        <h2 class="text-center">Bem-vindo à Nossa Loja</h2>
        <div class="row">
            <?php
            if (isset($_GET['search'])) {
                $search = $con->real_escape_string($_GET['search']);
                $sql = "SELECT * FROM produtos WHERE nome LIKE '%$search%' OR descricao LIKE '%$search%'";
            } else {
                $sql = "SELECT * FROM produtos";
            }
            $result = $con->query($sql);
            while ($row = $result->fetch_assoc()) { ?>
                <div class="col-md-4">
                    <div class="card mb-4">
                        <img src="<?= htmlspecialchars($row['imagem']); ?>" class="card-img-top" alt="<?= htmlspecialchars($row['nome']); ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($row['nome']); ?></h5>
                            <p class="card-text"><?= htmlspecialchars($row['descricao']); ?></p>
                            <p class="card-text"><strong>€<?= number_format($row['preco'], 2, ',', '.'); ?></strong></p>
                            <form action="auth.php" method="post">
                                <input type="hidden" name="produto_id" value="<?= $row['id']; ?>">
                                <input type="number" name="quantidade" value="1" min="1" class="form-control form-control-sm mb-2" style="width: 70px; display: inline-block;">
                                <input type="hidden" name="nome" value="<?= htmlspecialchars($row['nome']); ?>">
                                <input type="hidden" name="preco" value="<?= $row['preco']; ?>">
                                <button type="submit" class="btn btn-success w-100" value="AdicionarAoCarrinho" name="submit">Adicionar ao Carrinho</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>