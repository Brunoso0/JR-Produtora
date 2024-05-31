<?php
session_start();

// Definir o nível de acesso padrão como 0
$access_level = 0;

if (!isset($_SESSION['userid'])) {
    header("Location: ../index.php");
    exit();
}

// Obter os dados do usuário atualmente logado
$current_user_id = $_SESSION['userid'];
$current_user_access_level = $_SESSION['access_level'];

// Definir o nível de acesso atual como o nível de acesso do usuário logado
$access_level = $current_user_access_level;

// Include do arquivo de conexão com o banco de dados
include('db_connection.php');

// Variável para armazenar mensagens de sucesso ou erro
$message = "";
$error_message = "";

// Lógica para cadastrar novo usuário
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['new_user'])) {
    if ($current_user_access_level >= 2) {
        // Obtenha os dados do formulário
        $username = $_POST['username'];
        $password = $_POST['password'];
        $access_level = $_POST['access_level'];

        // Criptografar a senha
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Verificar se o nome de usuário já existe
        $check_username_sql = "SELECT * FROM users WHERE username = ?";
        $check_username_stmt = $conn->prepare($check_username_sql);
        $check_username_stmt->bind_param('s', $username);
        $check_username_stmt->execute();
        $result = $check_username_stmt->get_result();

        if ($result->num_rows > 0) {
            $error_message = "Nome de usuário já existe. Escolha outro.";
        } else {
            // Inserir novo usuário no banco de dados
            $insert_user_sql = "INSERT INTO users (username, password, access_level) VALUES (?, ?, ?)";
            $insert_user_stmt = $conn->prepare($insert_user_sql);
            $insert_user_stmt->bind_param('ssi', $username, $hashed_password, $access_level);

            if ($insert_user_stmt->execute()) {
                $message = "Usuário registrado com sucesso!";
                
                // Restaurar as informações do usuário atualmente logado
                $_SESSION['userid'] = $current_user_id;
                $_SESSION['access_level'] = $current_user_access_level;
            } else {
                $error_message = "Erro ao registrar usuário: " . $insert_user_stmt->error;
            }

            $insert_user_stmt->close();
        }
    } else {
        $error_message = "Você não tem permissão para cadastrar novos usuários.";
    }
}

// Lógica para atualizar o link do vídeo
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_video'])) {
    // Somente usuários com nível de acesso 1 ou superior podem atualizar o link do vídeo
    if ($access_level >= 1) {
        // Obtenha o link do vídeo do formulário
        $video1 = $_POST['video1'];

        // Obter os links dos vídeos anteriores
        $sql = "SELECT video1, video2, video3, video4 FROM videos WHERE id = 1";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();

        // Atualize os links dos vídeos anteriores
        $sql = "UPDATE videos SET video1 = ?, video2 = ?, video3 = ?, video4 = ? WHERE id = 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssss', $video1, $row['video1'], $row['video2'], $row['video3']);

        if ($stmt->execute()) {
            $message = "Link do vídeo atualizado com sucesso.";

            // Define a variável de sessão live_video
            $_SESSION['live_video'] = $video1;
        } else {
            $error_message = "Erro ao atualizar o link do vídeo: " . $stmt->error;
        }
    } else {
        $error_message = "Você não tem permissão para atualizar o link do vídeo.";
    }
}

// Lógica para o upload de imagens
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['fileToUpload'])) {
    // Somente usuários com nível de acesso 2 ou superior podem enviar imagens
    if ($access_level >= 2) {
        // Diretório onde as imagens serão armazenadas
        $target_dir = "uploads/";

        // Verificar se o diretório de upload existe, se não, criá-lo
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        // Contador para controlar o número de imagens enviadas
        $image_count = count($_FILES["fileToUpload"]["name"]);

        // Loop através de cada imagem enviada
        for ($i = 0; $i < $image_count; $i++) {
            $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"][$i]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

            // Verificar se o arquivo de imagem é uma imagem real ou uma imagem falsa
            if(isset($_POST["submit"])) {
                $check = getimagesize($_FILES["fileToUpload"]["tmp_name"][$i]);
                if($check !== false) {
                    $uploadOk = 1;
                } else {
                    $error_message = "O arquivo não é uma imagem.";
                    $uploadOk = 0;
                }
            }

            // Verificar se o arquivo já existe
            if (file_exists($target_file)) {
                $error_message = "Desculpe, o arquivo já existe.";
                $uploadOk = 0;
            }

            // Verificar o tamanho do arquivo
            if ($_FILES["fileToUpload"]["size"][$i] > 500000) {
                $error_message = "Desculpe, seu arquivo é muito grande.";
                $uploadOk = 0;
            }

            // Permitir apenas certos formatos de arquivo
            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif" ) {
                $error_message = "Desculpe, apenas arquivos JPG, JPEG, PNG e GIF são permitidos.";
                $uploadOk = 0;
            }

            // Verificar se $uploadOk está configurado como 0 por um erro
            if ($uploadOk == 0) {
                $error_message = "Desculpe, seu arquivo não foi enviado.";
            // Se tudo estiver ok, tentar enviar o arquivo
            } else {
                if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"][$i], $target_file)) {
                    // Inserir o caminho da imagem no banco de dados
                    $insert_image_sql = "INSERT INTO gallery (image_path, category, date) VALUES (?, ?, ?)";
                    $insert_image_stmt = $conn->prepare($insert_image_sql);
                    $insert_image_stmt->bind_param('sss', $target_file, $_POST['category'], $_POST['date']);

                    if ($insert_image_stmt->execute()) {
                        $message = "Imagem(s) enviada(s) com sucesso.";
                    } else {
                        $error_message = "Erro ao enviar imagem(s): " . $insert_image_stmt->error;
                    }
                } else {
                    $error_message = "Desculpe, ocorreu um erro ao enviar seu arquivo.";
                }
            }
        }
    } else {
        $error_message = "Você não tem permissão para enviar imagens.";
    }
}

// Lógica para adicionar e remover banners
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Adicionar banner
    if (isset($_POST['add_banner'])) {
        $target_dir = "banners/";
        
        // Verificar se a pasta existe, caso contrário, criá-la
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $target_file = $target_dir . basename($_FILES["banner_image"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Verificar se o arquivo de imagem é uma imagem real
        $check = getimagesize($_FILES["banner_image"]["tmp_name"]);
        if($check === false) {
            $error_message = "O arquivo não é uma imagem.";
            $uploadOk = 0;
        }

        // Verificar se o arquivo já existe
        if (file_exists($target_file)) {
            $error_message = "Desculpe, o arquivo já existe.";
            $uploadOk = 0;
        }

        // Verificar o tamanho do arquivo (opcional)
        if ($_FILES["banner_image"]["size"] > 5000000) {
            $error_message = "Desculpe, seu arquivo é muito grande.";
            $uploadOk = 0;
        }

        // Permitir apenas certos formatos de arquivo
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
            $error_message = "Desculpe, apenas arquivos JPG, JPEG, PNG e GIF são permitidos.";
            $uploadOk = 0;
        }

        // Verificar se $uploadOk está configurado como 0 por um erro
        if ($uploadOk == 0) {
            $error_message = "Desculpe, seu arquivo não foi enviado.";
        // Se tudo estiver ok, tentar enviar o arquivo
        } else {
            if (move_uploaded_file($_FILES["banner_image"]["tmp_name"], $target_file)) {
                // Adicionar o caminho do banner ao banco de dados
                $sql = "INSERT INTO banners (image_path) VALUES ('$target_file')";
                if ($conn->query($sql) === TRUE) {
                    $message = "O banner foi enviado com sucesso.";
                } else {
                    $error_message = "Erro ao salvar o banner no banco de dados.";
                }
            } else {
                $error_message = "Desculpe, ocorreu um erro ao enviar seu arquivo.";
            }
        }
    }
    
    // Remover banner
    if (isset($_POST['remove_banner'])) {
        $banner_id = $_POST['remove_banner'];
        // Obter o caminho do banner a ser removido
        $sql = "SELECT image_path FROM banners WHERE id=$banner_id";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $image_path = $row['image_path'];
            // Remover o banner do banco de dados
            $sql = "DELETE FROM banners WHERE id=$banner_id";
            if ($conn->query($sql) === TRUE) {
                // Remover o arquivo do servidor
                if (unlink($image_path)) {
                    $message = "Banner removido com sucesso.";
                } else {
                    $error_message = "Erro ao remover o arquivo do servidor.";
                }
            } else {
                $error_message = "Erro ao remover o banner do banco de dados.";
            }
        } else {
            $error_message = "Banner não encontrado.";
        }
    }
}
// Obter os dados dos banners do banco de dados
$sql = "SELECT * FROM banners";
$result = $conn->query($sql);



// Lógica para atualizar os eventos
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_events'])) {
    for ($i = 1; $i <= 3; $i++) {
        $day = $_POST["day_$i"];
        $title = $_POST["title_$i"];
        $description = $_POST["description_$i"];
        
        $update_event_sql = "UPDATE events SET day = ?, title = ?, description = ? WHERE id = ?";
        $update_event_stmt = $conn->prepare($update_event_sql);
        $update_event_stmt->bind_param('issi', $day, $title, $description, $i);

        if (!$update_event_stmt->execute()) {
            $error_message = "Erro ao atualizar o evento $i: " . $update_event_stmt->error;
        }
    }
    if (empty($error_message)) {
        $message = "Eventos atualizados com sucesso!";
    }
}

// Obter os dados dos eventos
$events_sql = "SELECT * FROM events";
$events_result = $conn->query($events_sql);
$events = [];
while ($row = $events_result->fetch_assoc()) {
    $events[] = $row;
}




// Fechar a conexão com o banco de dados
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Página Admin</title>
    <link rel="stylesheet" href="../css/telaAdmin.css">
</head>
<body>
<div class="admin-container">
    
    <div class="admin-container">
        <!-- Janela modal para exibir alertas -->
        <div id="myModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <p id="modal-message"></p>
            </div>
        </div>
        
        <!-- CADASTRO DE USUARIOS -->

        <h1>Página de Administração</h1>
    <?php if ($current_user_access_level >= 3): ?>
        <div class="form-section">
            <h2>Cadastrar Novo Usuário</h2>
            <form method="post" action="admin_page.php">
                <input type="hidden" name="new_user" value="1">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="access_level">Access Level:</label>
                    <select id="access_level" name="access_level" required>
                        <option value="1">User</option>
                        <option value="2">Admin</option>
                        <option value="3">Super Admin</option>
                    </select>
                </div>
                <input type="submit" value="Cadastrar">
            </form>
        </div>
    <?php endif; ?>

            <!-- LINK DA LIVE -->

    <?php if ($current_user_access_level >= 1): ?>
        <div class="form-section">
            <h2>Enviar Link da Live</h2>
            <form method="post" action="admin_page.php">
                <input type="hidden" name="update_video" value="1">
                <div class="form-group">
                    <label for="video1">Live Ao Vivo:</label>
                    <input type="text" id="video1" name="video1" value="<?php echo isset($row['video1']) ? $row['video1'] : ''; ?>" required>
                </div>
                <input type="submit" value="Salvar">
            </form>
        </div>
    <?php endif; ?>

            <!-- UPLOAD DE IMAGENS -->

    <?php if ($current_user_access_level >= 2 ): ?>
        <div class="form-section">
            <h2>Upload de Imagens</h2>
            <form action="admin_page.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="fileToUpload">Selecione as imagens para upload (máximo de 20):</label>
                    <input type="file" name="fileToUpload[]" id="fileToUpload" multiple required accept="image/*">
                </div>
                <div class="form-group">
                    <label for="category">Categoria:</label>
                    <select name="category" id="category">
                        <option value="circuito">SANJU NO CIRCUITO</option>
                        <option value="calcadao">SANJU NO CALÇADÃO</option>
                        <option value="praca">SANJU NA PRAÇA</option>
                        <option value="bairros">SANJU NOS BAIRROS</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="date">Data:</label>
                    <input type="date" name="date" id="date" required>
                </div>
                <input type="submit" value="Upload Imagens" name="submit">
            </form>
        </div>
    <?php endif; ?>

        <!-- Adicionar e remover banners na navbar da página inicial -->
    <?php if ($current_user_access_level >= 2 ): ?>
        <div class="form-section">
        <h2>Gerenciar Banners</h2>

            <?php if (isset($message)) echo "<p>$message</p>"; ?>
            <?php if (isset($error_message)) echo "<p>$error_message</p>"; ?>

            <form action="admin_page.php" method="post" enctype="multipart/form-data">
                <label for="banner_image">Adicionar Banner:</label>
                <input type="file" id="banner_image" name="banner_image" accept="image/*" required>
                <input type="submit" name="add_banner" value="Adicionar Banner">
            </form>

            <h3>Banners Existentes</h3>
            <form action="admin_page.php" method="post">
                <label for="remove_banner">Remover Banner:</label>
                <select name="remove_banner" id="remove_banner" required>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <option value="<?php echo $row['id']; ?>"><?php echo basename($row['image_path']); ?></option>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <option value="">Nenhum banner disponível</option>
                    <?php endif; ?>
                </select>
                <input type="submit" value="Remover Banner">
            </form>
        </div>
    <?php endif; ?>

    <?php if ($current_user_access_level >= 2): ?>
        <div class="form-section">
            <h2>Editar Eventos</h2>
            <form method="post" action="admin_page.php">
                <input type="hidden" name="update_events" value="1">
                <?php foreach ($events as $index => $event): ?>
                    <div class="form-group">
                        <label for="day_<?php echo $index+1; ?>">Dia:</label>
                        <input type="number" id="day_<?php echo $index+1; ?>" name="day_<?php echo $index+1; ?>" value="<?php echo $event['day']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="title_<?php echo $index+1; ?>">Título:</label>
                        <input type="text" id="title_<?php echo $index+1; ?>" name="title_<?php echo $index+1; ?>" value="<?php echo $event['title']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="description_<?php echo $index+1; ?>">Descrição:</label>
                        <textarea id="description_<?php echo $index+1; ?>" name="description_<?php echo $index+1; ?>" required><?php echo $event['description']; ?></textarea>
                    </div>
                <?php endforeach; ?>
                <input type="submit" value="Atualizar Eventos">
            </form>
        </div>
        <?php endif; ?>


        <a href="logout.php" class="logout-button">Sair</a>
    </div>

    <script>
        // Exibir janela modal com mensagem
        function showModal(message) {
            var modal = document.getElementById('myModal');
            var modalMessage = document.getElementById('modal-message');
            modalMessage.innerHTML = message;
            modal.style.display = 'block';
            setTimeout(function() {
                modal.style.display = 'none';
            }, 3000); // Esconder a janela modal após 3 segundos
        }

        // Chamar a função para exibir alertas
        <?php if (!empty($message)): ?>
            showModal('<?php echo $message; ?>');
        <?php endif; ?>
        <?php if (!empty($error_message)): ?>
            showModal('<?php echo $error_message; ?>');
        <?php endif; ?>

        // Fechar a janela modal ao clicar no botão Fechar (×)
        var closeBtn = document.getElementsByClassName('close')[0];
        if (closeBtn) {
            closeBtn.onclick = function() {
                var modal = document.getElementById('myModal');
                modal.style.display = 'none';
            }
        }
    </script>
    </div>
</body>
</html>

