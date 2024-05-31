<?php
include('php/db_connection.php');

$sql = "SELECT * FROM gallery";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/galeria.css">
    <title>Galeria</title>
</head>
<body>
    <div class="bg-navbar">
        <div class="navbar">
            <div class="Logo">
                <img src="img/JR PRODUTORA.svg" alt="LOGO">
            </div>
            <div class="categories">
                <div class="dropdown">
                    <a href="#" class="category" data-filter="all">Todas</a>
                </div>
                <div class="dropdown">
                    <a href="#" class="category" data-filter="circuito">SANJU NO CIRCUITO</a>
                    <div class="dropdown-content">
                        <!-- Preencher dinamicamente as datas -->
                        <?php
                        $sql_dates = "SELECT DISTINCT date FROM gallery WHERE category = 'circuito'";
                        $result_dates = $conn->query($sql_dates);
                        while($row_date = $result_dates->fetch_assoc()) {
                            echo '<a href="#" class="date" data-filter="'.date('dm', strtotime($row_date['date'])).'">'.date('d/m', strtotime($row_date['date'])).'</a>';
                        }
                        ?>
                    </div>
                </div>
                <div class="dropdown">
                    <a href="#" class="category" data-filter="calcadao">SANJU NO CALÇADÃO</a>
                    <div class="dropdown-content">
                        <?php
                        $sql_dates = "SELECT DISTINCT date FROM gallery WHERE category = 'calcadao'";
                        $result_dates = $conn->query($sql_dates);
                        while($row_date = $result_dates->fetch_assoc()) {
                            echo '<a href="#" class="date" data-filter="'.date('dm', strtotime($row_date['date'])).'">'.date('d/m', strtotime($row_date['date'])).'</a>';
                        }
                        ?>
                    </div>
                </div>
                <div class="dropdown">
                    <a href="#" class="category" data-filter="praca">SANJU NA PRAÇA</a>
                    <div class="dropdown-content">
                        <?php
                        $sql_dates = "SELECT DISTINCT date FROM gallery WHERE category = 'praca'";
                        $result_dates = $conn->query($sql_dates);
                        while($row_date = $result_dates->fetch_assoc()) {
                            echo '<a href="#" class="date" data-filter="'.date('dm', strtotime($row_date['date'])).'">'.date('d/m', strtotime($row_date['date'])).'</a>';
                        }
                        ?>
                    </div>
                </div>
                <div class="dropdown">
                    <a href="#" class="category" data-filter="bairros">SANJU NOS BAIRROS</a>
                    <div class="dropdown-content">
                        <?php
                        $sql_dates = "SELECT DISTINCT date FROM gallery WHERE category = 'bairros'";
                        $result_dates = $conn->query($sql_dates);
                        while($row_date = $result_dates->fetch_assoc()) {
                            echo '<a href="#" class="date" data-filter="'.date('dm', strtotime($row_date['date'])).'">'.date('d/m', strtotime($row_date['date'])).'</a>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="carousel">
        <div class="slide">
            <img src="img/BANNER.jpg" alt="Imagem 1">
        </div>
        <div class="slide">
            <img src="img/banner-verde.jpeg" alt="Imagem 2">
        </div>
        <div class="slide">
            <img src="img/banner-vermelho.jpeg" alt="Imagem 3">
        </div>
        <button class="prev" onclick="plusSlides(-1)">&#10094;</button>
        <button class="next" onclick="plusSlides(1)">&#10095;</button>
    </div>

    <div class="gallery">
<?php
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo '<img src="php/'.$row['image_path'].'" alt="Imagem" data-category="'.$row['category'].'" data-date="'.date('dm', strtotime($row['date'])).'" class="gallery-img" onclick="openModal()">';
    }
} else {
    echo "Nenhuma imagem encontrada.";
}
?>
</div>

    <!-- Modal -->
    <div id="myModal" class="modal">
        <span class="close">&times;</span>
        <img class="modal-content" id="modalImage">
        <div id="caption"></div>
        <button id="prevButtonModal" class="modal-prev">&lt;</button>
        <button id="downloadButton" class="modal-download">
            <svg
              class="mysvg"
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 24 24"
              height="24px"
              width="24px"
            >
              <g stroke-width="0" id="SVGRepo_bgCarrier"></g>
              <g
                stroke-linejoin="round"
                stroke-linecap="round"
                id="SVGRepo_tracerCarrier"
              ></g>
              <g id="SVGRepo_iconCarrier">
                <g id="Interface / Download">
                  <path
                    stroke-linejoin="round"
                    stroke-linecap="round"
                    stroke-width="2"
                    stroke="#f1f1f1"
                    d="M6 21H18M12 3V17M12 17L17 12M12 17L7 12"
                    id="Vector"
                  ></path>
                </g>
              </g>
            </svg>
            <span class="texto">Download</span>
          </button>
          
        <button id="nextButtonModal" class="modal-next">&gt;</button>
    </div>

    <script src="js/GaleriaPage.js"></script>
    <script src="js/modal-galery.js"></script> 
</body>
</html>

<?php
// Feche a conexão aqui, fora da estrutura HTML para evitar erros.
$conn->close();
?>
