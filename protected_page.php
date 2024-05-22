<?php
session_start();

if (!isset($_SESSION['username']) || !isset($_SESSION['access_level'])) {
    header("Location: index.html");
    exit();
}

// Definindo a descrição do nível de acesso
$access_description = ($_SESSION['access_level'] == 1) ? "Básico" : "Avançado";
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Página Protegida</title>
</head>
<body>
    <h1>Página Protegida</h1>
    <p>Bem-vindo, <?php echo $_SESSION['username']; ?>! Seu nível de acesso é <?php echo $access_description; ?>.</p>
    <a href="logout.php">Sair</a>
</body>
</html>
