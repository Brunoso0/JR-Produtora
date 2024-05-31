<?php
session_start();
include('db_connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Buscar o hash da senha no banco de dados
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashed_password = $row['password'];
        
        // Verificar a senha
        if (password_verify($password, $hashed_password)) {
            $_SESSION['userid'] = $row['id'];
            $_SESSION['access_level'] = $row['access_level'];

            if ($row['access_level'] == 1 || $row['access_level'] == 2 || $row['access_level'] == 3) {
                header("Location: admin_page.php");
                exit();
            } else {
                header("Location: ../index.php");
                exit();
            }
        } else {
            header("Location: LoginInvalido.php");
            exit();
        }
    } else {
        header("Location: LoginInvalido.php");
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>
