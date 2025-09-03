<?php

// Configura o relatório de erros do MySQLi para facilitar a depuração
mysqli_report(MYSQLI_REPORT_ERROR);

// Se você optou por utilizar um arquivo de segredos, inclua-o:
// require_once 'secrets.php';
// altera os parâmetros da conexão conforme necessário
// $con = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Estabelece a conexão com o banco de dados MySQL
$con = new mysqli("localhost", "root", "", "loja_php");

// Verifica se houve erro na conexão
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}
