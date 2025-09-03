<?php


require_once 'db.php'; // Inclusão do arquivo de conexão com o banco de dados
require_once 'email.php'; // Inclusão do arquivo de manipulação de e-mails
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start(); // Inicia a sessão caso não esteja ativa
}
/**
 * Login de um utilizador
 * @param string $username -> Nome de utilizador ou email
 * @param string $password -> Password do utilizador
 * @return bool -> true se o login foi bem sucedido, false caso contrário
 */

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Coletar os dados do formulário
    // $nome = $_POST['nome'];
    // $email = $_POST['email'];
    switch ($_POST['submit']) {
        case "AdicionarAoCarrinho":
            if (isset($_POST['produto_id']) && isset($_POST['quantidade'])) {
                $produto_id = intval($_POST['produto_id']);
                $quantidade = intval($_POST['quantidade']);
                adicionarAoCarrinho($quantidade, $produto_id);
            }
            break;
        case "AdicionarProduto":
            // Adiciona um novo produto ao sistema
            $nome = $_POST['nome'];
            $descricao = $_POST['descricao'];
            $preco = $_POST['preco'];
            $imagem = $_POST['imagem'];
            $stock = $_POST['stock'];
            adicionarProduto($nome, $descricao, $preco, $imagem, $stock);
            break;
        case "Apagar":
            apagarConta();
            // Apaga uma conta de utilizador
            break;
        case "AtualizarCarrinho":
            // Atualiza a quantidade de um produto no carrinho
            if (isset($_POST["produtoId"]) && isset($_POST["quantidade"])) {
                $userId = $_SESSION["user_id"];
                $produtoId = $con->real_escape_string($_POST["produtoId"]);
                $quantidade = $con->real_escape_string($_POST["quantidade"]);
                atualizarCarrinho($quantidade, $userId, $produtoId);
            }
            break;
        case "EditarProduto":
            // Edita um produto existente
            $id = $_POST['id'];
            $nome = $_POST['nome'];
            $descricao = $_POST['descricao'];
            $preco = $_POST['preco'];
            $imagem = $_POST['imagem'];
            $stock = $_POST['stock'];
            editarProduto($id, $nome, $descricao, $preco, $imagem, $stock);
            break;
        case "Login":
            // Realiza o login de um utilizador
            $username = $_POST['username'];
            $password = $_POST['password'];
            login($username, $password);
            break;
        case "Logout":
            // Realiza o logout do utilizador
            logout();
            break;
        case "Recuperacao":
            // Recupera a senha do utilizador
            $email = $_POST['email'];
            recuperacao_senha($email);
            break;
        case "Registo":
            // Regista um novo utilizador
            $email = $_POST['email'];
            $username = $_POST['username'];
            $password = $_POST['password'];
            $telemovel = $_POST['telemovel'];
            $nif = $_POST['nif'];
            registo($email, $username, $password, $telemovel, $nif);
            break;
        case "RemoverDoCarrinho":
            // Remove um produto do carrinho
            if (isset($_POST["produtoId"])) {
                $userId = $_SESSION["user_id"];
                $produtoId = $con->real_escape_string($_POST["produtoId"]);
                removerDoCarrinho($userId, $produtoId);
            }
            break;
        case "Reset":
            // Redefine a senha do utilizador
            $token = $_POST['token'];
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];
            reset_senha($token, $password, $confirm_password);
            break;
        case "Validar":
            // Valida a conta do utilizador
            $email = $_POST['email'];
            $token = $_POST['token'];
            ativarConta($email, $token);
            break;
    }
}
function adicionarAoCarrinho($quantidade, $produto_id)
{
    global $con;
    // Verifica se o produto já está no carrinho do utilizador
    $sql = $con->prepare("SELECT quantidade FROM carrinho WHERE produtoId = ? AND userId = ?");
    $sql->bind_param("ii", $produto_id, $_SESSION['user_id']);
    $sql->execute();
    $result = $sql->get_result();
    if ($result->num_rows > 0) {
        // Produto já existe no carrinho, atualizar a quantidade
        $row = $result->fetch_assoc();
        $nova_quantidade = $row['quantidade'] + $quantidade;
        $update_sql = $con->prepare("UPDATE carrinho SET quantidade = ? WHERE produtoId = ? AND userId = ?");
        $update_sql->bind_param("iii", $nova_quantidade, $produto_id, $_SESSION['user_id']);
        $update_sql->execute();
    } else {
        // Produto não existe no carrinho, adicionar novo item
        $insert_sql = $con->prepare("INSERT INTO carrinho (produtoId, userId, quantidade) VALUES (?, ?, ?)");
        $insert_sql->bind_param("iii", $produto_id, $_SESSION['user_id'], $quantidade);
        $insert_sql->execute();
    }
    // Redireciona para a página do carrinho
    header("Location: carrinho.php");
}
function adicionarProduto($nome, $descricao, $preco, $imagem, $stock)
{
    global $con;
    // Inserir produto na base de dados
    $sql = $con->prepare("INSERT INTO produtos (nome, descricao, preco, imagem, stock) VALUES (?, ?, ?, ?, ?)");
    $sql->bind_param("ssdsi", $nome, $descricao, $preco, $imagem, $stock);
    if ($sql->execute()) {
        header("Location: admin.php");
    } else {
        echo "Erro ao adicionar produto.";
    }
}
function apagarConta()
{
    global $con;
    if (!isset($_SESSION['user_id'])) {
        return false; // O utilizador não está autenticado
    }
    $user_id = $_SESSION['user_id'];
    // Preparar a query para apagar o utilizador
    $sql = $con->prepare("DELETE FROM utilizador WHERE id = ?");
    $sql->bind_param('i', $user_id);
    $sql->execute();
    if ($sql->affected_rows > 0) {
        // Se a conta foi apagada, encerra a sessão e direciona para a pagina de Login
        session_destroy();
        header("Location: login.php");
        exit;
    } else {
        return false; // Falha ao apagar a conta
    }
}
function ativarConta($email, $token)
{
    global $con;
    // 1º - Verificar se o utilizador com o email e token existe
    $sql = $con->prepare("SELECT * FROM utilizador WHERE email = ? AND token = ?");
    $sql->bind_param('ss', $email, $token);
    $sql->execute();
    $result = $sql->get_result();
    if ($result->num_rows > 0) {
        // 2º - Se existir, atualizar active para 1 e remove o token
        $sql_update = $con->prepare("UPDATE utilizador SET active = 1, token = NULL WHERE email = ?");
        $sql_update->bind_param('s', $email);
        if ($sql_update->execute()) {
            echo "Conta Validada com sucesso!";
        } else {
            echo "Erro na Validação" . $sql->error;
        }
        if ($sql_update->affected_rows > 0) {
            return true; // Conta ativada com sucesso
        } else {
            return false; // Falha na ativação
        }
    } else {
        // 3º - Se não existir, retornar false
        return false;
    }
}
function atualizarCarrinho($quantidade, $userId, $produtoId)
{
    global $con;
    if ($quantidade <= 0) {
        // Se a quantidade for menor ou igual a zero, remover o produto do carrinho
        $sql = $con->prepare("DELETE FROM carrinho WHERE userId = ? AND produtoId = ?");
        $sql->bind_param("ii", $userId, $produtoId);
    } else {
        // Atualiza a quantidade do produto no carrinho
        $sql = $con->prepare("UPDATE carrinho SET quantidade = ? WHERE userId = ? AND produtoId = ?");
        $sql->bind_param("iii", $quantidade, $userId, $produtoId);
    }
    if ($sql->execute()) {
        header("Location: carrinho.php");
        exit();
    } else {
        echo "Erro ao atualizar carrinho.";
    }
}
function editarProduto($id, $nome, $descricao, $preco, $imagem, $stock)
{
    global $con;
    // Atualizar produto na base de dados
    $sql = $con->prepare("UPDATE produtos SET nome = ?, descricao = ?, preco = ?, imagem = ?, stock = ? WHERE id = ?");
    $sql->bind_param("ssdsii", $nome, $descricao, $preco, $imagem, $stock, $id);
    if ($sql->execute()) {
        header("Location: admin.php");
    } else {
        echo "Erro ao editar produto.";
    }
}
function isAdmin()
{
    // Verifica se o utilizador tem permissões de administrador
    return (isset($_SESSION["role_id"]) && $_SESSION["role_id"] == 1);
}
function login($username, $password)
{
    require_once 'db.php'; // Certifica que este ficheiro conecta à base de dados
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        global $con;
        // Verifica se o utilizador tem permissões de administrador
        $sql = $con->prepare("SELECT id, username, email, RoleID, password FROM utilizador WHERE (username = ? OR email = ?) AND active = 1");
        $sql->bind_param('ss', $username, $username);
        $sql->execute();
        $result = $sql->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            // Verificar a password
            if (password_verify($password, $row["password"])) {
                // Armazena os dados do utilizador na sessão
                $_SESSION["user_id"] = $row["id"];
                $_SESSION["username"] = $row["username"];
                $_SESSION["email"] = $row["email"];
                $_SESSION["role_id"] = $row["RoleID"];
                // Redirecionar para a loja após login bem-sucedido
                header("Location: loja.php");
                exit;
            } else {
                echo "Erro: Palavra-passe incorreta.";
            }
        } else {
            echo "Erro: Utilizador não encontrado ou conta inativa.";
        }
        $sql->close();
    }
}
function logout()
{
    session_unset(); // Limpar todas as variáveis da sessão
    session_destroy(); // Destruir a sessão
    // Redirecionar o utilizador para a página de login
    header("Location: login.php");
    exit;
}
function recuperacao_senha($email)
{
    global $con;
    // 1️⃣ Verificar se o email existe na base de dados
    $sql = $con->prepare("SELECT id FROM utilizador WHERE email = ?");
    $sql->bind_param('s', $email);
    $sql->execute();
    $result = $sql->get_result();
    if ($result->num_rows > 0) {
        // 2️⃣ Gerar um token único para redefinir a senha
        $token = bin2hex(random_bytes(16));
        // 3️⃣ Guardar o token na base de dados
        $sql_update = $con->prepare("UPDATE utilizador SET token = ? WHERE email = ?");
        $sql_update->bind_param('ss', $token, $email);
        $sql_update->execute();
        if ($sql_update->affected_rows > 0) {
            // 4️⃣ Enviar um email com o token para redefinir a senha
            send_email($email, "Recuperação de Senha", "Utilize o seguinte código para validar a nova password!\nCódigo de Validação: " . $token);
            // Reencaminhar para a página de reset_senha
            require_once 'reset_senha.php';
            return true; // Email enviado com sucesso
        } else {
            return false; // Erro ao atualizar o token
        }
    } else {
        return false; // Email não encontrado
    }
}
/**
 * Registo de um novo utilizador
 * @param string $email     -> Email do utilizador
 * @param string $username  -> Nome de utilizador
 * @param string $password  -> Password do utilizador
 * @param string $telemovel -> Número de telemóvel
 * @param string $nif       -> Número de Identificação Fiscal
 * @return bool -> true se o registo foi bem sucedido, false caso contrário
 */
function registo($email, $username, $password, $telemovel, $nif)
{
    global $con;
    //1º - Criar e preparar a query de insert
    $sql = $con->prepare('INSERT INTO utilizador(email,username,password,telemovel,nif,token,RoleID) VALUES (?,?,?,?,?,?,2)');
    //2º - Gerar o token aletório
    $token = bin2hex(random_bytes(16));
    //3º - Encriptar a password
    $password = password_hash($password, PASSWORD_DEFAULT);
    //4º - Colocar os dados na query e executar a mesma e ver se deu certo
    $sql->bind_param('ssssss', $email, $username, $password, $telemovel, $nif, $token);
    if ($sql->execute()) {
        echo "Registo realizado com sucesso!";
        sleep(2);
        require_once 'validar_registo.php';
    } else {
        echo "Erro no registo: " . $sql->error;
    }
    if ($sql->affected_rows > 0) {
        //5º - Enviar o email com o token para ativar a conta
        send_email($email, 'Ativar a conta', "Utilize o seguinte código para validar o registo.\nCódigo de registo: " . $token);
        return true;
    } else {
        //O registo falhou
        return false;
    }
}
function removerDoCarrinho($userId, $produtoId)
{
    global $con;
    $sql = $con->prepare("DELETE FROM carrinho WHERE userId = ? AND produtoId = ?");
    $sql->bind_param("ii", $userId, $produtoId);
    if ($sql->execute()) {
        header("Location: carrinho.php");
        exit();
    } else {
        echo "Erro ao remover produto do carrinho.";
    }
}
function reset_senha($token, $password, $confirm_password)
{
    global $con;
    if ($password !== $confirm_password) {
        die("As senhas não coincidem.");
    }
    // Criptografar a nova senha
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    // Verificar se o token é válido
    $sql = "SELECT email FROM utilizador WHERE token = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        // Atualizar a senha na base de dados
        $stmt->bind_result($email);
        $stmt->fetch();
        $sql = "UPDATE utilizador SET password = ?, token = NULL, active = 1 WHERE email = ?";
        $stmt2 = $con->prepare($sql);
        $stmt2->bind_param("ss", $password_hash, $email);
        if ($stmt2->execute()) {
            echo "Senha redefinida com sucesso!";
        } else {
            echo "Erro ao atualizar a senha.";
        }
    } else {
        echo "Token inválido ou expirado.";
    }
    $stmt->close();
}
$con->close();
