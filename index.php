<?php
session_start();
include('php/db_connection.php');

// Obter os dados dos vídeos do banco de dados
$sql = "SELECT * FROM videos WHERE id = 1";
$result = $conn->query($sql);
$video_data = $result->fetch_assoc();

// Obter os dados dos Banners do banco de dados 
$sql ="SELECT * FROM banners";
$result = $conn->query($sql); 

// Obter dados dos eventos
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
    <!-- FONTS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <!-- FONTS  -->
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/responsividade.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JR PRODUTORA</title>
</head>
<body>
    <div class="NavBar-total">
        <!-- imagem da Logo -->
        <a href="#">
            <img class="logo-navbar" src="img/JR PRODUTORA.svg" alt="">
        </a>
        <nav class="Navbar">
            <ul class="itens-navbar">
                <li><a class="link" href="cadastrando.php">Quem somos</a></li>
                <li class="dropdown">
                    <a class="link" href="#">Programas</a>
                    <div class="dropdown-content">
                        <a href="#">Café com Resenhas</a>
                        <a href="#">JR esportes</a>
                        <a href="#">JR Noticias</a>
                    </div>
                </li>
                <li><a class="link" href="#">Seja Parceiro</a></li>
                <li><a class="link" href="#">Entre em Contato</a></li>
            </ul> 
        </nav>
    </div>
    <div class="carousel">
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="slide">
                    <img src="php/<?php echo $row['image_path']; ?>" alt="Banner">
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Nenhum banner disponível.</p>
        <?php endif; ?>
        <button class="prev" onclick="plusSlides(-1)">&#10094;</button>
        <button class="next" onclick="plusSlides(1)">&#10095;</button>
    </div>
    
    <main>
    <div class="agenda">
    <h1 class="titulo-agenda">AGENDA</h1>
    <?php foreach ($events as $event): ?>
        <div class="container-evento">
            <div class="evento">
                <div class="numero"><?php echo sprintf("%02d", $event['day']); ?></div>
                <div class="textos">
                    <h1 id="title-event" class="nome-evento"><?php echo htmlspecialchars($event['title']); ?></h1>
                    <p class="descricao-evento"><?php echo htmlspecialchars($event['description']); ?></p>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>



        
<div class="Lives">
        <h1 id="AOVIVO">ESTAMOS AO VIVO AGORA!!</h1>
        <div class="Live-Agora">
            <?php
            // Verificar se a sessão do vídeo ao vivo está definida
            if (isset($_SESSION['live_video'])) {
                $live_video = $_SESSION['live_video'];
                // Exibir o vídeo ao vivo
                echo "<iframe width='560' height='315' src='$live_video' frameborder='0' allowfullscreen></iframe>";
            } else if (isset($video_data['video1'])) {
                // Exibir o vídeo ao vivo a partir do banco de dados
                echo "<iframe width='560' height='315' src='{$video_data['video1']}' frameborder='0' allowfullscreen></iframe>";
            } else {
                // Exibir uma mensagem caso nenhum vídeo esteja disponível
                echo "Nenhum vídeo disponível no momento.";
            }
            ?>
        </div>
        <div class="fundo-escuro-lives">
            <img src="img/bg 2.png" class="imagem-de-fundo" alt="Imagem de fundo">
            <h1>Lives Passadas</h1>
            <div class="Lives-Antigas">
                <div class="live">
                    <iframe width="560" height="315" src="<?php echo $video_data['video2']; ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                </div>
                <div class="live">
                    <iframe width="560" height="315" src="<?php echo $video_data['video3']; ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                </div>
                <div class="live">
                    <iframe width="560" height="315" src="<?php echo $video_data['video4']; ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>


        <div class="canal">
            <h2>Acesse agora nosso canal no Youtube</h1>
                <a href="https://www.youtube.com/@jrprodutora" target="_blank">
                <button class="Btn">
                    <span class="svgContainer">
                      <svg
                        viewBox="0 0 576 512"
                        fill="white"
                        height="1.6em"
                        xmlns="http://www.w3.org/2000/svg"
                      >
                        <path
                          d="M549.7 124.1c-6.3-23.7-24.8-42.3-48.3-48.6C458.8 64 288 64 288 64S117.2 64 74.6 75.5c-23.5 6.3-42 24.9-48.3 48.6-11.4 42.9-11.4 132.3-11.4 132.3s0 89.4 11.4 132.3c6.3 23.7 24.8 41.5 48.3 47.8C117.2 448 288 448 288 448s170.8 0 213.4-11.5c23.5-6.3 42-24.2 48.3-47.8 11.4-42.9 11.4-132.3 11.4-132.3s0-89.4-11.4-132.3zm-317.5 213.5V175.2l142.7 81.2-142.7 81.2z"
                        ></path>
                      </svg>
                    </span>
                    <span class="BG"></span>
                  </button>
                </a>
        </div>
        <div class="van-e-equipamentos">
            <img src="img/van.png" alt="Van">
            <div class="van-textos">
                <h1>LOREM IPSUM</h1>
                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500sLorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s</p>
                <div class="van-button">
                    <a href="#">
                        <button id="btn">Conhecer</button>
                    </a>
                </div>
            </div>
        </div>
        <h1 class="title">GALERIA DE FOTOS</h1>
    <div class="wrapper">
        <div class="gallery-container">
            <div class="gallery-item"><img src="img/galeria.jpeg" alt="Foto 1"></div>
            <div class="gallery-item"><img src="img/galeria.jpeg" alt="Foto 2"></div>
            <div class="gallery-item"><img src="img/galeria.jpeg" alt="Foto 3"></div>
            <div class="gallery-item"><img src="img/galeria.jpeg" alt="Foto 4"></div>
            <div class="gallery-item"><img src="img/galeria.jpeg" alt="Foto 5"></div>
        </div>
    </div>
    <a class="galery-btn" href="galery.php">
        <button class="btn-galery">Ver Mais</button>
    </a>
    <div id="background-celular">
        <div class="celular-redes">
            <div class="celular">
                <img src="img/celular.png" alt="Celular">
            </div>
            <div class="celular-textos">
                <h1>LOREM IPSUM</h1>
                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500sLorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s</p>
                <div class="btn-celular">
                    <div class="button-celular"><a href="#">Ver Mais</a></div>
                </div>    
            </div>
        </div>
    </div>
    </main>
    <footer class="footer-bg">
        <div class="footer">
        <div class="footer-column">
            <img src="img/JR PRODUTORA.svg" alt="Logo da Empresa" class="footer-logo">
            <div class="footer-phones">
                <p>+55 xx xxxx-xxxx</p>
                <p>+55 xx xxxx-xxxx</p>
                <p>+55 xx xxxx-xxxx</p>
            </div>
        </div>
        <div class="footer-column" id="meio">
            <h3>Redes Sociais</h3>
            <div class="social-icons">
                <div class="social-icon">
                    <img src="img/insta.png" alt="Instagram">
                    <span>@JRProdutoraOficial</span>
                </div>
                <div class="social-icon">
                    <img src="img/youtube.png" alt="Youtube">
                    <span>JR Produtora</span>
                </div>
            </div>
        </div>
        <div class="footer-column">
            <h3>Localização</h3>
            <div class="location-map">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3923.476966673535!2d-40.18991622307471!3d-10.463015114442108!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x76d597515e46e67%3A0xf36ba74cbff32544!2sJr%20graphic%20e%20Jr%20produtora!5e0!3m2!1spt-BR!2sbr!4v1716382393957!5m2!1spt-BR!2sbr" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </div>
    <div class="desenvolvedores">
        <h3>Desenvolvedores</h3>
        <ul>
            <li>Bruno Santos - brunosantosbrito.dev@gmail.com</li>
            <li>Eduardo Henrique - designerhenriqueoc@gmail.com</li>
            <li>Taylan Lima - taylandesign@hotmail.com</li>
        </ul>
        <p>&copy; 2024</p>
    </div>
</footer>
    <script src="js/Galery.js"></script>
    <script src="js/carrossel-index.js"></script>
</body>
</html>
