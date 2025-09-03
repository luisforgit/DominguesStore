<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}
?>
<!DOCTYPE html>
<html lang="pt">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Página Inicial</title>
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
    rel="stylesheet" />
  <style>
    body {
      background: linear-gradient(135deg, #f8fafc 0%, #e3e6ed 100%);
      min-height: 100vh;
    }

    .welcome-card {
      max-width: 420px;
      margin: 80px auto;
      padding: 32px 24px;
      border-radius: 16px;
      box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
      background: #fff;
    }

    .store-title {
      font-weight: 700;
      color: #2c3e50;
    }

    .btn-primary {
      font-size: 1.1rem;
      padding: 10px 32px;
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="welcome-card text-center">
      <h1 class="store-title mb-3">Bem-vindo à Domingues' Store</h1>
      <p class="mb-4 text-secondary">Faça Login no botão abaixo:</p>
      <a href="login.php" class="btn btn-primary mb-2 w-100">Login Loja</a>
      <?php if (isset($_SESSION['user_id'])): ?>
        <a href="loja.php" class="btn btn-outline-success w-100 mt-2">Ir para Loja</a>
        <?php if (!empty($_SESSION['role_id']) && $_SESSION['role_id'] == 1): ?>
          <a href="admin.php" class="btn btn-outline-dark w-100 mt-2">Área Administrativa</a>
        <?php endif; ?>
        <a href="logout.php" class="btn btn-outline-danger w-100 mt-2">Logout</a>
      <?php endif; ?>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>