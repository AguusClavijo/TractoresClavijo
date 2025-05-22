<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Remera - Tractores Clavijo</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="../../css/style.css" />
    <link rel="stylesheet" href="../../css/contact.css" />
    <link rel="stylesheet" href="../merch.css" />  <link rel="stylesheet" href="remera.css" />   </head>
<body class="merch-page"> <header>
        <nav class="navbar navbar-expand-lg navbar-dark fixed-top navbar-custom">
            <div class="container">
                <a class="navbar-brand" href="../../main/main.php">Tractores Clavijo</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto align-items-center">
                        <li class="nav-item">
                            <a class="nav-link" href="../../main/main.php">Inicio</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../../about/about.php">Sobre Nosotros</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../../tractors/tractors.php">Tractores</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../../contact/contact.php">Contacto</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="../merch.php">Merch</a> </li>
                        <li class="nav-item">
                            <a class="btn btn-login" href="../../login/login.php">Iniciar Sesión</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-cart" href="../../cart/cart.php"><i class="fa-solid fa-cart-shopping"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <main style="padding-top: var(--navbar-height);">

        <section class="merch-header">
            <div class="container">
                <h1>Remera con nuestro Logo</h1>
                <p class="lead">Explora las características de nuestra remera exclusiva.</p>
            </div>
        </section>

        <section class="merch-products-section">
            <div class="container product-detail-container">
                <div class="row">
                    <div class="col-lg-6 col-md-12 product-detail-image">
                        <div class="main-image-wrapper">
                            <img src="../img/11.png" alt="Remera Logo Tractores Clavijo" class="img-fluid product-detail-main-img">
                        </div>
                        <div class="thumbnail-images mt-3 d-flex justify-content-center">
                            <img src="../img/11.png" alt="Miniatura 1" class="thumbnail-img active">
                            </div>
                    </div>

                    <div class="col-lg-6 col-md-12 product-detail-info">
                        <h2 class="product-detail-title">Remera con Logo Tractores Clavijo</h2>
                        <p class="product-detail-price">$25.000 ARS</p>

                        <div class="product-detail-description">
                            <p>Esta remera de algodón premium es la elección perfecta para los verdaderos entusiastas de Tractores Clavijo. Su diseño moderno y el logo icónico te permiten llevar tu pasión a donde quiera que vayas. Ideal para el uso diario o para eventos especiales.</p>
                            <ul>
                                <li>Material: 100% Algodón peinado de alta calidad.</li>
                                <li>Diseño: Estampado duradero del logo frontal.</li>
                                <li>Confort: Ajuste regular, transpirable y suave al tacto.</li>
                                <li>Durabilidad: Resistente al lavado y al uso continuo.</li>
                            </ul>
                        </div>

                        <div class="product-options mt-4">
                            <div class="mb-3 product-option-item">
                                <label for="size" class="form-label">Talle:</label>
                                <select class="form-select" id="size">
                                    <option selected disabled>Seleccione un talle</option>
                                    <option>S</option>
                                    <option>M</option>
                                    <option>L</option>
                                    <option>XL</option>
                                    <option>XXL</option>
                                </select>
                            </div>
                            <div class="mb-3 product-option-item">
                                <label for="color" class="form-label">Color:</label>
                                <select class="form-select" id="color">
                                    <option selected disabled>Seleccione un color</option>
                                    <option>Negro</option>
                                    <option>Blanco</option>
                                    <option>Gris Melange</option>
                                </select>
                            </div>
                            <div class="mb-3 product-option-item">
                                <label for="quantity" class="form-label">Cantidad:</label>
                                <input type="number" class="form-control" id="quantity" value="1" min="1">
                            </div>
                        </div>

                        <div class="product-actions mt-5">
                            <button class="btn btn-success btn-lg me-2">
                                <i class="fa-solid fa-cart-plus me-2"></i>Añadir al Carrito
                            </button>
                            <a href="../merch.php" class="btn btn-outline-secondary btn-lg">
                                <i class="fa-solid fa-arrow-left me-2"></i>Volver a Merch
                            </a>
                        </div>

                        <div class="delivery-info mt-4 pt-3 border-top">
                            <p><i class="fa-solid fa-truck-fast me-2"></i>Envío rápido a todo el país.</p>
                            <p><i class="fa-solid fa-credit-card me-2"></i>Pagos seguros con tarjeta y otros medios.</p>
                            <p><i class="fa-solid fa-shield-alt me-2"></i>Política de devolución de 30 días.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-4 mb-4 mb-md-0">
                    <h5>Tractores Clavijo</h5>
                    <p>Conecte con nosotros en nuestras redes sociales y manténgase al día con las últimas novedades.</p>
                    <a href="https://www.instagram.com/tractores.clavijo/" target="_blank" class="d-block text-decoration-none mb-2">
                        <i class="bi bi-instagram"></i> @tractores.clavijo
                    </a>
                    <a href="https://www.facebook.com/profile.php?id=100089567695602" target="_blank" class="d-block text-decoration-none">
                        <i class="bi bi-facebook"></i> Tractores Clavijo
                    </a>
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
                        <li><a href="../../main/main.php"><i class="bi bi-caret-right-fill"></i> Inicio</a></li>
                        <li><a href="../../about/about.php"><i class="bi bi-caret-right-fill"></i> Sobre Nosotros</a></li>
                        <li><a href="../../tractors/tractors.php"><i class="bi bi-caret-right-fill"></i> Tractores</a></li>
                        <li><a href="../../contact/contact.php"><i class="bi bi-caret-right-fill"></i> Contacto</a></li>
                        <li><a href="../../merch/merch.php"><i class="bi bi-caret-right-fill"></i> Merch</a></li>
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