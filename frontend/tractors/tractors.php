<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tractores - Tractores Clavijo</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="tractors.css" />
    <link rel="stylesheet" href="../css/contact.css" />
    </head>

<body class="tractors-page">
    <?php
    // Activar la visualización de errores de PHP para depuración
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    // Definición del array de tractores
    // Las rutas de 'src' son relativas a la carpeta 'tractors' (donde está tractors.php)
    // Las rutas de 'pagina' son ahora relativas a la carpeta 'tractors' también, porque 'models-tractors' está dentro.
    $tractores = [
        ['id' => 'tractor1', 'src' => 'fotos/1.jpg', 'alt' => 'Tractor RA 254', 'modelo' => 'RA 254', 'hp' => '25 HP', 'pagina' => 'models-tractors/ra-254.php'],
        ['id' => 'tractor2', 'src' => 'fotos/2.jpg', 'alt' => 'Tractor RD 304', 'modelo' => 'RD 304', 'hp' => '30 HP', 'pagina' => 'models-tractors/rd-304.php'],
        ['id' => 'tractor3', 'src' => 'fotos/3.jpg', 'alt' => 'Tractor RD 404', 'modelo' => 'RD 404', 'hp' => '40 HP', 'pagina' => 'models-tractors/rd-404.php'],
        ['id' => 'tractor4', 'src' => 'fotos/4.jpg', 'alt' => 'Tractor RK 454', 'modelo' => 'RK 454', 'hp' => '45 HP', 'pagina' => 'models-tractors/rk-454.php'],
        ['id' => 'tractor5', 'src' => 'fotos/5.jpg', 'alt' => 'Tractor RA 504', 'modelo' => 'RA 504', 'hp' => '50 HP', 'pagina' => 'models-tractors/ra-504.php'],
        ['id' => 'tractor6', 'src' => 'fotos/6.jpg', 'alt' => 'Tractor RA 504-F', 'modelo' => 'RA 504-F', 'hp' => '50 HP', 'pagina' => 'models-tractors/ra-504-f.php'],
        ['id' => 'tractor7', 'src' => 'fotos/7.jpg', 'alt' => 'Tractor RD 504', 'modelo' => 'RD 504', 'hp' => '50 HP', 'pagina' => 'models-tractors/rd-504.php'],
        ['id' => 'tractor8', 'src' => 'fotos/8.jpg', 'alt' => 'Tractor RK 504 CON TECHO', 'modelo' => 'RK 504 CON TECHO', 'hp' => '50 HP', 'pagina' => 'models-tractors/rk-504-techo.php'],
        ['id' => 'tractor9', 'src' => 'fotos/9.jpg', 'alt' => 'Tractor RK 504 CON CABINA', 'modelo' => 'RK 504 CON CABINA', 'hp' => '50 HP', 'pagina' => 'models-tractors/rk-504-cabina.php'],
        ['id' => 'tractor10', 'src' => 'fotos/10.jpg', 'alt' => 'Tractor RA 704', 'modelo' => 'RA 704', 'hp' => '70 HP', 'pagina' => 'models-tractors/ra-704.php'],
        ['id' => 'tractor11', 'src' => 'fotos/11.jpg', 'alt' => 'Tractor RK 704 CON TECHO', 'modelo' => 'RK 704 CON TECHO', 'hp' => '70 HP', 'pagina' => 'models-tractors/rk-704-techo.php'],
        ['id' => 'tractor12', 'src' => 'fotos/12.jpg', 'alt' => 'Tractor RK 704 CON CABINA', 'modelo' => 'RK 704 CON CABINA', 'hp' => '70 HP', 'pagina' => 'models-tractors/rk-704-cabina.php'],
        ['id' => 'tractor13', 'src' => 'fotos/13.jpg', 'alt' => 'Tractor RK 754 CON TECHO', 'modelo' => 'RK 754 CON TECHO', 'hp' => '75 HP', 'pagina' => 'models-tractors/rk-754-techo.php'],
        ['id' => 'tractor14', 'src' => 'fotos/14.jpg', 'alt' => 'Tractor RK 754 CON CABINA', 'modelo' => 'RK 754 CON CABINA', 'hp' => '75 HP', 'pagina' => 'models-tractors/rk-754-cabina.php'],
        ['id' => 'tractor15', 'src' => 'fotos/15.jpg', 'alt' => 'Tractor RK 904 CON TECHO', 'modelo' => 'RK 904 CON TECHO', 'hp' => '90 HP', 'pagina' => 'models-tractors/rk-904-techo.php'],
        ['id' => 'tractor16', 'src' => 'fotos/16.jpg', 'alt' => 'Tractor RK 904 CON CABINA', 'modelo' => 'RK 904 CON CABINA', 'hp' => '90 HP', 'pagina' => 'models-tractors/rk-904-cabina.php'],
        ['id' => 'tractor17', 'src' => 'fotos/17.jpg', 'alt' => 'Tractor RC 1004 CON TECHO', 'modelo' => 'RC 1004 CON TECHO', 'hp' => '100 HP', 'pagina' => 'models-tractors/rc-1004-techo.php'],
        ['id' => 'tractor18', 'src' => 'fotos/18.jpg', 'alt' => 'Tractor RC 1004 CON CABINA', 'modelo' => 'RC 1004 CON CABINA', 'hp' => '100 HP', 'pagina' => 'models-tractors/rc-1004-cabina.php'],
        ['id' => 'tractor19', 'src' => 'fotos/19.jpg', 'alt' => 'Tractor RC 1104 CON TECHO', 'modelo' => 'RC 1104 CON TECHO', 'hp' => '110 HP', 'pagina' => 'models-tractors/rc-1104-techo.php'],
        ['id' => 'tractor20', 'src' => 'fotos/20.jpg', 'alt' => 'Tractor RC 1104 CON CABINA', 'modelo' => 'RC 1104 CON CABINA', 'hp' => '110 HP', 'pagina' => 'models-tractors/rc-1104-cabina.php'],
        ['id' => 'tractor21', 'src' => 'fotos/21.jpg', 'alt' => 'Tractor RC 1404', 'modelo' => 'RC 1404', 'hp' => '140 HP', 'pagina' => 'models-tractors/rc-1404.php'],
    ];
    ?>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark fixed-top navbar-custom">
            <div class="container">
                <a class="navbar-brand" href="../main/main.php">Tractores Clavijo</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="../main/main.php">Inicio</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../about/about.php">Sobre Nosotros</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="tractors.php">Tractores</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../contact/contact.php">Contacto</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../merch/merch.php">Merch</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-login ms-3" href="../login/login.php">Iniciar Sesión</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-cart ms-2" href="../cart/cart.php"><i class="fa-solid fa-cart-shopping"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <main>
        <section class="tractors-header text-white text-center">
            <div class="container">
                <h1>NUESTROS TRACTORES</h1>
                <p class="lead">Explora nuestra gama de tractores, diseñados para cada necesidad agrícola.</p>
            </div>
        </section>

        <section class="tractors-products-section py-5">
            <div class="container">
                <h2 class="text-center mb-5">Modelos Disponibles</h2>
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                    <?php foreach ($tractores as $tractor) : ?>
                        <div class="col">
                            <a href="<?php echo $tractor['pagina']; ?>" class="product-card-link">
                                <div class="product-card text-center">
                                    <img src="<?php echo $tractor['src']; ?>" class="img-fluid rounded mb-3" alt="<?php echo $tractor['alt']; ?>">
                                    <h3 class="product-title"><?php echo $tractor['modelo']; ?></h3>
                                    <p class="product-price"><?php echo $tractor['hp']; ?></p>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    </main>

    <footer class="footer-custom">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-4 mb-4 mb-md-0">
                    <h5>Tractores Clavijo</h5>
                    <p>Conéctate con nosotros en nuestras redes sociales.</p>
                    <div class="social-icons">
                        <a href="https://www.instagram.com/tractores_clavijo/" target="_blank" class="text-white me-3"><i class="fab fa-instagram fa-lg"></i></a>
                        <a href="https://www.facebook.com/TractoresClavijo/" target="_blank" class="text-white"><i class="fab fa-facebook-f fa-lg"></i></a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 mb-4 mb-md-0">
                    <h5>Ubicación</h5>
                    <address>
                        Carril Santos Lugares Ingeniero Giagnoni<br>
                        Mendoza - ARGENTINA<br>
                        <i class="bi bi-telephone-fill"></i> Teléfono: 0263 452-6714
                    </address>
                </div>
                <div class="col-lg-4 col-md-4 mb-4 mb-md-0">
                    <h5>Enlaces Rápidos</h5>
                    <ul class="list-unstyled">
                        <li><a href="../main/main.php"><i class="bi bi-caret-right-fill"></i> Inicio</a></li>
                        <li><a href="../about/about.php"><i class="bi bi-caret-right-fill"></i> Sobre Nosotros</a></li>
                        <li><a href="tractors.php"><i class="bi bi-caret-right-fill"></i> Tractores</a></li>
                        <li><a href="#"><i class="bi bi-caret-right-fill"></i> Repuestos</a></li>
                        <li><a href="../contact/contact.php"><i class="bi bi-caret-right-fill"></i> Contacto</a></li>
                    </ul>
                </div>
            </div>
            <div class="text-center border-top pt-3 mt-4">
                <small>© <?php echo date("Y"); ?> Tractores Clavijo - Todos los derechos reservados</small>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>