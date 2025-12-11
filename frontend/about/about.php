<?php session_start();
$cart_count = 0;
if (isset($_SESSION['carrito']) && is_array($_SESSION['carrito'])) {
    foreach ($_SESSION['carrito'] as $item_cart) {
        if (isset($item_cart['cantidad'])) {
            $cart_count += $item_cart['cantidad']; // Suma las cantidades de cada producto
        }
    }
}
?>

<?php
// Poner esto al inicio de tus archivos PHP o en un config.php
// session_start(); // Ya lo tienes
$script_path_parts = explode('/', dirname($_SERVER['SCRIPT_NAME']));
$project_root_segment = (isset($script_path_parts[1]) && !empty($script_path_parts[1]) && $script_path_parts[1] !== 'frontend' && $script_path_parts[1] !== 'backend') ? '/' . $script_path_parts[1] : '';
// Si tu proyecto está en localhost/TractoresClavijo/, $project_root_segment será /TractoresClavijo
// Si tu proyecto está en localhost/, $project_root_segment será ''
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sobre Nosotros - Tractores Clavijo</title>
    <link rel="icon" href="<?php echo $project_root_segment; ?>/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="<?php echo $project_root_segment; ?>/favicon.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/81448e9ee5.js" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="../css/about.css" />

</head>

<body class="about-page">
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark fixed-top navbar-custom">
            <div class="container">
                <a class="navbar-brand" href="<?php echo $project_root_segment; ?>/frontend/main/main.php">Tractores Clavijo</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavGlobal" aria-controls="navbarNavGlobal" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-end" id="navbarNavGlobal">
                    <ul class="navbar-nav align-items-center">
                        <li class="nav-item">
                            <a class="nav-link <?php echo (strpos($_SERVER['PHP_SELF'], 'main.php') !== false ? 'active' : ''); ?>" href="<?php echo $project_root_segment; ?>/frontend/main/main.php">Inicio</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo (strpos($_SERVER['PHP_SELF'], 'about.php') !== false ? 'active' : ''); ?>" href="<?php echo $project_root_segment; ?>/frontend/about/about.php">Sobre Nosotros</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo (strpos($_SERVER['PHP_SELF'], 'tractors.php') !== false ? 'active' : ''); ?>" href="<?php echo $project_root_segment; ?>/frontend/tractors/tractors.php">Tractores</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo (strpos($_SERVER['PHP_SELF'], 'contact.php') !== false ? 'active' : ''); ?>" href="<?php echo $project_root_segment; ?>/frontend/contact/contact.php">Contacto</a>
                        </li>

                        <?php if (isset($_SESSION['user_id'])): ?>
                            <li class="nav-item dropdown ms-lg-3">
                                <a class="nav-link dropdown-toggle btn btn-user-dropdown" href="#" id="navbarUserDropdownGlobal" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Hola, <?php echo htmlspecialchars($_SESSION['user_nombre']); ?> <i class="bi bi-person-circle ms-1"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarUserDropdownGlobal">
                                    <li><a class="dropdown-item" href="../perfil/perfil.php">Mi Perfil</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item" href="<?php echo $project_root_segment; ?>/backend/php/logout.php">Cerrar Sesión</a></li>
                                </ul>
                            </li>
                        <?php else: ?>
                            <li class="nav-item ms-lg-3">
                                <?php // En login.php, este es un botón. En otras páginas, es un enlace.
                                if (basename($_SERVER['PHP_SELF']) === 'login.php') : ?>
                                    <button class="btn btn-login" id="loginPopupBtn" type="button">Iniciar Sesión</button>
                                <?php else: ?>
                                    <a class="btn btn-login" href="<?php echo $project_root_segment; ?>/frontend/login/login.php">Iniciar Sesión</a>
                                <?php endif; ?>
                            </li>
                        <?php endif; ?>

                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <main style="padding-top: var(--navbar-height);">

        <section class="about-header">
            <div class="container">
                <h1>Nuestra Historia</h1>
                <p class="lead">Una trayectoria familiar dedicada al campo mendocino, creciendo con pasión y compromiso.</p>
            </div>
        </section>

        <section class="about-section">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <h2>De la Mecánica a la Representación Oficial</h2>
                        <p>Tractores Clavijo es una empresa familiar con raíces profundas en el corazón agrícola de Mendoza, ubicada en Ingeniero Giagnoni, Junín. Nuestra historia comenzó hace más de 15 años, dedicándonos con pasión y esfuerzo a la venta de tractores usados y a la mecánica especializada en maquinaria agrícola. Con el tiempo, gracias al trabajo constante, el conocimiento adquirido y la confianza de nuestros clientes, fuimos creciendo paso a paso.</p>
                        <p>En el año 2021, decidimos dar un salto importante e incursionar en la venta de tractores 0 km, convirtiéndonos en representantes oficiales de tractores Chery by Lion, una marca reconocida por su confiabilidad, eficiencia y excelente relación precio-calidad. Desde entonces, nos hemos posicionado como una de las principales referencias de la marca en la región.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="about-section bg-light py-5">
            <div class="container">
                <h2>Consolidando la Confianza</h2>
                <p class="text-center mb-4">Con más de 400 unidades Chery by Lion vendidas en toda la provincia de Mendoza, nuestro compromiso con la calidad, la atención personalizada y el acompañamiento postventa ha sido clave. No vendemos solo un tractor: ofrecemos una solución completa, asesoramiento técnico y un vínculo a largo plazo basado en el respeto y la responsabilidad.</p>

                <div class="highlight-box">
                    <h3>Reconocimiento Nacional</h3>
                    <p>Nuestro crecimiento y dedicación han sido reconocidos a nivel nacional, un reflejo del esfuerzo diario de todo nuestro equipo.</p>
                </div>

                <div class="row achievements-section text-center">
                    <div class="col-md-6">
                        <div class="achievements-list">
                            <li><strong>2023:</strong> 2º Lugar en ranking anual de ventas Chery by Lion en Argentina.</li>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="achievements-list">
                            <li><strong>2024:</strong> 1º Puesto en ranking anual de ventas Chery by Lion en Argentina.</li>
                        </div>
                    </div>
                </div>
                <p class="mt-4">Actualmente contamos con dos locales comerciales estratégicamente ubicados para brindar una atención rápida, directa y profesional. Cada espacio está diseñado para ofrecer asesoramiento integral, exhibición de modelos, repuestos y servicio técnico, con mecánicos capacitados y herramientas específicas para garantizar la mayor durabilidad y rendimiento de cada unidad.</p>
            </div>
        </section>

        <section class="about-section">
            <div class="container">
                <h2>¿Qué nos Diferencia?</h2>
                <div class="row">
                    <div class="col-md-10 mx-auto">
                        <ul class="differentiators-list">
                            <li>
                                <i class="bi bi-people-fill"></i>
                                <div>
                                    <strong>Atención personalizada:</strong> Nos tomamos el tiempo para escuchar, entender tus necesidades y asesorarte con honestidad.
                                </div>
                            </li>
                            <li>
                                <i class="bi bi-tools"></i>
                                <div>
                                    <strong>Experiencia en el rubro:</strong> Más de 15 años trabajando exclusivamente con maquinaria agrícola.
                                </div>
                            </li>
                            <li>
                                <i class="bi bi-headset"></i>
                                <div>
                                    <strong>Servicio postventa real:</strong> No solo vendemos, sino que acompañamos a nuestros clientes en el mantenimiento y reparación de sus tractores.
                                </div>
                            </li>
                            <li>
                                <i class="bi bi-graph-up-arrow"></i>
                                <div>
                                    <strong>Precios competitivos y financiación accesible:</strong> Sabemos lo que implica invertir en el campo y buscamos facilitar cada operación.
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <section class="about-section final-commitment">
            <div class="container">
                <h2 class="text-center">Nuestro Compromiso</h2>
                <p class="text-center">En Tractores Clavijo creemos en el trabajo bien hecho, en la cercanía con el productor y en la importancia de ofrecer herramientas que realmente sumen al desarrollo del campo. Somos una familia con ganas de seguir creciendo, de seguir construyendo el futuro que queremos y de dejar una huella positiva en la comunidad rural mendocina.</p>
                <p class="text-center fw-bold mt-4">Te invitamos a conocernos, visitarnos y ser parte de nuestra historia.</p>
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
                        <li><a href="#"><i class="bi bi-caret-right-fill"></i> Sobre Nosotros</a></li>
                        <li><a href="../tractors/tractors.php"><i class="bi bi-caret-right-fill"></i> Tractores</a></li>
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