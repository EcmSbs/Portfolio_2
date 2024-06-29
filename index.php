<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Principal</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Menu de Opções -->
    <header>
        <nav>
            <ul class="menu">
                <li><a href="#">Home</a></li>
                <li><a href="#">Sobre</a></li>
                <li class="servicos"><a href="#">Serviços</a>
                    <ul class="submenu">
                        <li><a href="sistema.php">Sistema</a></li>
                    </ul>
                </li>
                <li><a href="#">Contato</a></li>
            </ul>
        </nav>
    </header>

    <!-- Carrossel de Imagens -->
    <section class="carousel">
        <div class="slides">
            <div class="slide"><img src="imagens/image1.jpg" alt="Imagem 1"></div>
            <div class="slide"><img src="imagens/image2.jpg" alt="Imagem 2"></div>
            <div class="slide"><img src="imagens/image3.jpg" alt="Imagem 3"></div>
            <div class="slide"><img src="imagens/image4.jpg" alt="Imagem 4"></div>
        </div>
        <button class="prev" onclick="changeSlide(-1)">&#10094;</button>
        <button class="next" onclick="changeSlide(1)">&#10095;</button>
    </section>

    <!-- Mídias Sociais -->
    <footer>
        <div class="social-media">
            <a href="#" class="social-icon">Facebook</a>
            <a href="#" class="social-icon">Twitter</a>
            <a href="#" class="social-icon">LinkedIn</a>
        </div>
    </footer>

    <script src="scripts.js"></script>
</body>
</html>
