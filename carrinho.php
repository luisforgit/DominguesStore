<?php

require_once 'auth.php'; // Inclui o sistema de autenticação

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start(); // Inicia a sessão se ainda não estiver ativa
}
// Redireciona o utilizador para a página de login caso não esteja autenticado
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

require_once 'db.php'; // Conexão com a base de dados

// Recupera os produtos do carrinho do utilizador
$sql = $con->prepare("SELECT p.id, p.nome, p.descricao, p.preco, p.imagem, c.quantidade FROM produtos p JOIN carrinho c ON p.id = c.produtoId WHERE c.userId = ?");
$sql->bind_param("i", $_SESSION["user_id"]);
$sql->execute();
$result = $sql->get_result();
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho de Compras</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .cart-item {
            border: 1px solid #e3e3e3;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            background: #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
        }

        .cart-item img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
        }

        .cart-actions {
            display: flex;
            gap: 10px;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Carrinho de Compras</h2>
        <table class="table table-striped table-bordered text-center align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Imagem</th>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th>Preço Unitário</th>
                    <th>Quantidade</th>
                    <th>Subtotal</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <img src="<?php echo htmlspecialchars($row['imagem']); ?>" alt="Imagem" class="img-fluid rounded shadow-sm" style="max-width: 80px;">
                        </td>
                        <td class="fw-bold"><?php echo htmlspecialchars($row['nome']); ?></td>
                        <td class="text-muted"><?php echo htmlspecialchars($row['descricao']); ?></td>
                        <td class="fw-bold text-success"><?php echo number_format($row['preco'], 2, ',', '.'); ?> €</td>
                        <td>
                            <form action="auth.php" method="post" class="d-flex align-items-center justify-content-center gap-2">
                                <input type="hidden" name="produtoId" value="<?php echo $row['id']; ?>">
                                <input type="number" name="quantidade" value="<?php echo $row['quantidade']; ?>" min="1" class="form-control form-control-sm text-center" style="width: 70px;">
                                <button type="submit" class="btn btn-primary btn-sm" value="AtualizarCarrinho" name="submit">Atualizar</button>
                            </form>
                        </td>
                        <td class="fw-bold"><?php echo number_format($row["quantidade"] * $row['preco'], 2, ',', '.'); ?> €</td>
                        <td>
                            <form action="auth.php" method="post">
                                <input type="hidden" name="produtoId" value="<?php echo $row['id']; ?>">
                                <button type="submit" class="btn btn-danger btn-sm" value="RemoverDoCarrinho" name="submit">Remover</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <div class="text-center mt-4">
            <a href="loja.php" class="btn btn-primary px-4 py-2">Continuar a Comprar</a>
        </div>
        <?php
        // Reset result pointer and calculate total
        $result->data_seek(0);
        $total = 0;
        while ($row = $result->fetch_assoc()) {
            $total += $row["quantidade"] * $row["preco"];
        }
        ?>
        <?php if ($total > 0): ?>
            <div class="d-flex justify-content-end mt-4">
                <h4>Total do Pedido: <span class="badge bg-success"><?php echo number_format($total, 2, ',', '.'); ?> €</span></h4>
            </div>
        <?php endif; ?>
    </div>
    <div class="d-flex justify-content-center">
        <div id="paypal-button-container" class="w-50"></div>
    </div>
    <script src="https://www.paypal.com/sdk/js?client-id=AWtQmQPnCH2atx48OUYxhFOrFrp2bP35MyZbI1S1vxLQtI_OluvBOFSiAX4zzCPn6LHGy5ZJspSFsfNW&currency=EUR"></script>
    <script>
        paypal.Buttons({
            createOrder: function(data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: '<?php echo $total; ?>'
                        }
                    }]
                });
            },
            onApprove: function(data, actions) {
                return actions.order.capture().then(function(details) {
                    window.location.href = "final.php";
                });
            },
            onError: function(err) {
                console.error('Erro no pagamento:', err);
                alert('Ocorreu um erro durante o pagamento. Tente novamente.');
            }
        }).render('#paypal-button-container');
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>