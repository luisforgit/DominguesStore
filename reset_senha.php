<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir Senha</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .reset-container {
            width: 400px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            background-color: #f8f9fa;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="reset-container">
            <h2 class="text-center">Redefinir Senha</h2>
            <form action="auth.php" method="post">
                <div class="mb-3">
                    <label for="token" class="form-label">Introduza o Token</label>
                    <input type="text" class="form-control" id="token" name="token" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Nova Palavra-Passe</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="mb-3">
                    <label for="confirm_password" class="form-label">Confirmar Palavra-Passe</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100" value="Reset" name="submit">Redefinir Senha</button>
            </form>
        </div>
    </div>
</body>

</html>