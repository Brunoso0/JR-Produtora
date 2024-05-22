<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conexão com o banco de dados
    $servername = "localhost";
    $username = "root";
    $password = ""; // Sua senha do banco de dados aqui
    $dbname = "login_db";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }

    if (isset($_POST['username']) && isset($_POST['password'])) {
        $user = $_POST['username'];
        $pass = $_POST['password'];

        // Prevenir SQL Injection
        $user = $conn->real_escape_string($user);
        $pass = $conn->real_escape_string($pass);

        // Consulta SQL para verificar o usuário e senha
        $sql = "SELECT * FROM users WHERE username='$user' AND password='$pass'";
        $result = $conn->query($sql);

        if ($result->num_rows == 1) {
            // Usuário autenticado com sucesso
            $row = $result->fetch_assoc();
            $_SESSION['username'] = $user;
            $_SESSION['access_level'] = $row['access_level'];
            echo "Login bem-sucedido! Bem-vindo, " . $_SESSION['username'] . ".";
            // Redirecionar apenas se o login for bem-sucedido
            header("Location: protected_page.php");
            exit();
        } else {
            echo "Usuário ou senha incorretos.";
        }
    } else {
        echo "Por favor, preencha todos os campos.";
    }

    $conn->close();
}
?>
