<?php
$servername = "localhost"; // Nome do servidor MySQL
$username = "root";        // Nome de usuário do MySQL
$password = "";            // Senha do MySQL (em branco se não houver senha)
$dbname = "login_db"; // Nome do banco de dados

// Cria a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}
?>
