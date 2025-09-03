<?php
session_start();
require_once 'secrets.php';
require_once 'db.php';

// Recupera todos os produtos da base de dados
$sql = "SELECT * FROM produtos";
$result = $con->query($sql);
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administração da Loja</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php
    // Verifica se o utilizador tem permissões de administrador
    if (isset($_SESSION['role_id']) && $_SESSION['role_id'] === 1) {
    ?>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="admin.php">Administração da Loja</a>
                <div class="ms-auto">
                    <a class="btn btn-info ms-2" data-bs-toggle="modal" data-bs-target="#addProductModal">🔧 Adicionar Produto</a>
                    <a href="loja.php" class="btn btn-primary ms-2">🛍 Loja</a>
                    <a href="logout.php" class="btn btn-danger ms-2">🚪 Logout</a>
                </div>
            </div>
        </nav>
        <div class="container mt-5">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Descrição</th>
                        <th>Preço</th>
                        <th>Imagem</th>
                        <th>Stock</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?= $row['id']; ?></td>
                            <td><?= $row['nome']; ?></td>
                            <td><?= $row['descricao']; ?></td>
                            <td>€<?= number_format($row['preco'], 2, ',', '.'); ?></td>
                            <td><img src="<?= $row['imagem']; ?>" width="50"></td>
                            <td><?= $row['stock']; ?></td>
                            <td>
                                <button class="btn btn-warning btn-sm" onclick="loadEditModal(<?= $row['id']; ?>)">Editar</button>
                                <button class="btn btn-danger btn-sm" onclick="deleteProduct(<?= $row['id']; ?>)">Eliminar</button>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <!-- MODAL PARA ADICIONAR PRODUTO -->
        <div class="modal fade" id="addProductModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Adicionar Produto</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form action="auth.php" method="POST">
                            <div class="mb-3">
                                <label for="nome" class="form-label">Nome do Produto</label>
                                <input type="text" class="form-control" name="nome" required>
                            </div>
                            <div class="mb-3">
                                <label for="descricao" class="form-label">Descrição</label>
                                <textarea class="form-control" name="descricao" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="preco" class="form-label">Preço (€)</label>
                                <input type="number" class="form-control" name="preco" step="0.01" required>
                            </div>
                            <div class="mb-3">
                                <label for="imagem" class="form-label">Imagem (URL)</label>
                                <input type="text" class="form-control" name="imagem" required>
                            </div>
                            <div class="mb-3">
                                <label for="stock" class="form-label">Stock</label>
                                <input type="number" class="form-control" name="stock" required>
                            </div>
                            <button type="submit" class="btn btn-success w-100" value="AdicionarProduto" name="submit">Adicionar Produto</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="editProductModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content" id="editProductContent">
                    <!-- Conteúdo será carregado via AJAX -->
                </div>
            </div>
        </div>
    <?php
    } else {
        // Se o utilizador não for admin, redireciona para a página inicial
        header("Location: index.php");
        exit;
    }
    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function deleteProduct(id) {
            if (confirm("Tem certeza que deseja eliminar este produto?")) {
                window.location.href = "eliminar_produto.php?id=" + id;
            }
        }

        function loadEditModal(id) {
            fetch(`editar_produto.php?id=${id}`)
                .then(response => response.text())
                .then(html => {
                    document.getElementById('editProductContent').innerHTML = html;
                    var editModal = new bootstrap.Modal(document.getElementById('editProductModal'));
                    editModal.show();
                })
                .catch(error => {
                    console.error('Erro ao carregar modal:', error);
                });
        }
    </script>
</body>

</html>