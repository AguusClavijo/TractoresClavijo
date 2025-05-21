<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Especificaciones - Tractor RA504</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="merch.css" />
    <link rel="stylesheet" href="../css/contact.css" />

    <style>
        /* Estilos Específicos para la Página de Especificaciones */

        body.spec-page {
            background-color: #f8f9fa;
        }

        .spec-header {
            background-color: var(--primary-green);
            color: var(--light-text);
            padding: 4rem 1rem;
            text-align: center;
            margin-bottom: 3rem;
        }

        .spec-header h1 {
            font-size: 2.8rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .spec-header p.lead {
            font-size: 1.25rem;
            font-weight: 300;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        .tractor-image-container {
            text-align: center;
            margin-bottom: 3rem;
        }

        .tractor-image-container img {
            max-width: 80%;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .specifications-section {
            padding: 2rem 0;
        }

        .specifications-section h2 {
            color: var(--primary-green);
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 2rem;
            text-align: center;
        }

        .specifications-list {
            list-style: none;
            padding: 0;
            max-width: 700px;
            margin: 0 auto;
        }

        .specifications-list li {
            background-color: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 5px;
            margin-bottom: 0.8rem;
            padding: 1rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 1.1rem;
        }

        .specifications-list li strong {
            color: var(--dark-text);
            font-weight: 600;
        }

        .specifications-list li span {
            color: #555;
            text-align: right;
        }

        @media (max-width: 767.98px) {
            .spec-header h1 {
                font-size: 2.2rem;
            }
            .spec-header p.lead {
                font-size: 1.1rem;
            }
            .specifications-list li {
                flex-direction: column;
                align-items: flex-start;
                padding: 0.8rem 1rem;
                font-size: 1rem;
            }
            .specifications-list li span {
                text-align: left;
                margin-top: 0.2rem;
            }
            .tractor-image-container img {
                max-width: 95%;
            }
        }
    </style>
</head>

<body class="spec-page">
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark fixed-top navbar-custom">
            <div class="container">
                <a class="navbar-brand" href="../main/main.php">Tractores Clavijo</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto align-items-center">
                        <li class="nav-item">
                            <a class="nav-link" href="../main/main.php">Inicio</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../about/about.php">Sobre Nosotros</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../tractors/tractors.php">Tractores</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../contact/contact.php">Contacto</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../merch/merch.php">Merch</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-login" href="../login/login.php">Iniciar Sesión</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-cart" href="../cart/cart.php"><i class="fa-solid fa-cart-shopping"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <main style="padding-top: var(--navbar-height);">

        <section class="spec-header">
            <div class="container">
                <h1>Tractor RA504</h1>
                <p class="lead">Conozca las especificaciones técnicas detalladas de nuestro tractor RA504.</p>
            </div>
        </section>

        <section class="tractor-image-container">
            <img src="https://www.americanagro.com.ar/wp-content/uploads/2019/09/ra504.png" alt="Tractor RA504" class="img-fluid">
        </section>

        <section class="specifications-section">
            <div class="container">
                <h2>Especificaciones Técnicas</h2>
                <ul class="specifications-list">
                    <li><strong>Tracción:</strong> <span>Doble 4x4</span></li>
                    <li><strong>Motor:</strong> <span>Diésel 4 cilindros, inyección directa, bomba lineal, refrigerado por agua</span></li>
                    <li><strong>Potencia:</strong> <span>58 hp</span></li>
                    <li><strong>Cilindrada:</strong> <span>1255 cm3</span></li>
                    <li><strong>Velocidad de rotación:</strong> <span>3100 rpm</span></li>
                    <li><strong>Número de marchas:</strong> <span>8 hacia adelante / 2 hacia atrás</span></li>
                    <li><strong>Velocidad mínima-máxima (adelante / atrás):</strong> <span>2.35 - 33 / 2.06 - 27 km/h</span></li>
                    <li><strong>Freno principal:</strong> <span>Disco seco</span></li>
                    <li><strong>Capacidad de elevación a 610mm:</strong> <span>720 kg</span></li>
                    <li><strong>Peso:</strong> <span>1700 kg</span></li>
                </ul>
                <p class="text-center mt-4">Para más detalles, puede visitar la página original del producto: <a href="https://www.americanagro.com.ar/tractores/tractores-livianos-ra504/" target="_blank">Tractor RA504 - American Agro</a></p>
            </div>
        </section>
    </main>

    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-4 mb-4 mb-md-0">
                    <h5>Tractores Clavijo</h5>
                    <p>Conecte con nosotros en nuestras redes sociales y manténgase al día con las últimas novedades.</p>
                    <a href="https://www.instagram.com/tractoresclavijo_" target="_blank" class="d-block text-decoration-none mb-2">
                        <i class="bi bi-instagram"></i> @tractoresclavijo_
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
                        <li><a href="../main/main.php"><i class="bi bi-caret-right-fill"></i> Inicio</a></li>
                        <li><a href="../about/about.php"><i class="bi bi-caret-right-fill"></i> Sobre Nosotros</a></li>
                        <li><a href="../tractors/tractors.php"><i class="bi bi-caret-right-fill"></i> Tractores</a></li>
                        <li><a><i class="bi bi-caret-right-fill"></i> Repuestos</a></li>
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