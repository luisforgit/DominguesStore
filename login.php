<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
require_once 'secrets.php';
require_once 'db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Busca o utilizador pelo username ou email
    $stmt = $con->prepare('SELECT * FROM utilizador WHERE username = ? OR email = ?');
    $stmt->bind_param('ss', $username, $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role_id'] = $user['RoleID'];
        $_SESSION['username'] = $user['username'];
        if ($_SESSION['role_id'] == 1) {
            header('Location: admin.php');
        } else {
            header('Location: index.php');
        }
        exit;
    } else {
        $error = 'Usuário ou senha inválidos.';
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <title>Login - Loja PHP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #e3e6ed 0%, #f8fafc 100%);
            min-height: 100vh;
        }

        .login-card {
            max-width: 400px;
            margin: 80px auto;
            padding: 32px 24px;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
            background: #fff;
        }

        .login-title {
            font-weight: 700;
            color: #2c3e50;
        }

        .form-control:focus {
            box-shadow: 0 0 0 0.2rem #0d6efd33;
        }

        .btn-primary {
            font-size: 1.1rem;
            padding: 10px 32px;
        }

        .forgot-link {
            display: block;
            margin-top: 8px;
            font-size: 0.95rem;
            color: #0d6efd;
            text-decoration: none;
        }

        .forgot-link:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="login-card">
            <h2 class="mb-4 text-center login-title">Entrar na Loja</h2>
            <form method="post" autocomplete="off">
                <div class="mb-3">
                    <label for="username" class="form-label">Usuário ou Email</label>
                    <input type="text" name="username" id="username" class="form-control" placeholder="Digite seu usuário ou email" required autofocus>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Senha</label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Digite sua senha" required>
                </div>
                <button type="submit" class="btn btn-primary w-100 mb-2">Entrar</button>
                <a href="recuperacao_senha.php" class="forgot-link">Esqueceu a senha?</a>
            </form>
            <?php if ($error): ?>
                <div class="alert alert-danger mt-3 text-center"><?= $error ?></div>
            <?php endif; ?>
            <hr>
            <div class="text-center">
                <a href="index.php" class="btn btn-outline-secondary w-100">Voltar à Página Inicial</a>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>