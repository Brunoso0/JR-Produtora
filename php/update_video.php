<?php
session_start();
include('db_connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['live_video'])) {
    // Obter o novo vídeo ao vivo do formulário de administração
    $new_live_video = $_POST['live_video'];

    // Obter os vídeos atuais do banco de dados
    $sql = "SELECT * FROM videos WHERE id = 1";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    // Mover os vídeos
    $video4 = $row['video3'];
    $video3 = $row['video2'];
    $video2 = $row['video1'];
    $video1 = $new_live_video;

    // Atualizar os vídeos no banco de dados
    $sql = "UPDATE videos SET video1 = ?, video2 = ?, video3 = ?, video4 = ? WHERE id = 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssss', $video1, $video2, $video3, $video4);
    $stmt->execute();

    // Atualizar a sessão do vídeo ao vivo
    $_SESSION['live_video'] = $new_live_video;

    // Redirecionar de volta para a página de administração
    header("Location: admin.php");
    exit();
} else {
    // Se os dados do vídeo ao vivo não forem recebidos, redirecionar de volta para a página de administração
    header("Location: admin.php");
    exit();
}
?>