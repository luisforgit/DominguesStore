<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validação de Registo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .validation-container {
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
        <div class="validation-container">
            <h2 class="text-center">Validação de Registo</h2>
            <form action="auth.php" method="post">
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="token" class="form-label">Token de Ativação</label>
                    <input type="text" class="form-control" id="token" name="token" required>
                </div>
                <button type="submit" class="btn btn-primary w-100" value="Validar" name="submit">Validar Conta</button>
            </form>
        </div>
    </div>
</body>

</html>