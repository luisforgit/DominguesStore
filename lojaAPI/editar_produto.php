<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
require_once 'db.php'; // Inclui a conexão com a base de dados
$id = $_GET['id']; // Obtém o ID do produto via GET
// Recupera os dados do produto da base de dados
$sql = $con->prepare("SELECT * FROM produtos WHERE id = ?");
$sql->bind_param("i", $id);
$sql->execute();
$result = $sql->get_result();
$produto = $result->fetch_assoc();
?>
<div class="modal-header">
    <h5 class="modal-title">Editar Produto</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>
<div class="modal-body">
    <form action="auth.php" method="POST">
        <input type="hidden" name="id" value="<?= $produto['id']; ?>">
        <div class="mb-3">
            <label class="form-label">Nome do Produto</label>
            <input type="text" class="form-control" name="nome" value="<?= $produto['nome']; ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Descrição</label>
            <textarea class="form-control" name="descricao" required><?= $produto['descricao']; ?></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Preço (€)</label>
            <input type="number" class="form-control" name="preco" step="0.01" value="<?= $produto['preco']; ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Imagem (URL)</label>
            <input type="text" class="form-control" name="imagem" value="<?= $produto['imagem']; ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Stock</label>
            <input type="number" class="form-control" name="stock" value="<?= $produto['stock']; ?>" required>
        </div>
        <button type="submit" class="btn btn-warning w-100" value="EditarProduto" name="submit">Atualizar Produto</button>
    </form>
</div>