<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Contacto - Tractores Clavijo</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <link rel="stylesheet" href="../css/contact.css" />

</head>

<body class="contact-page">
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
                            <a class="nav-link active" aria-current="page" href="#">Contacto</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-login" href="../login/login.php">Iniciar Sesión</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <main style="padding-top: var(--navbar-height);">

        <section class="contact-header">
            <div class="container">
                <h1>Contáctanos</h1>
                <p class="lead">Estamos aquí para ayudarte. Encuentra la mejor forma de comunicarte con nosotros.</p>
            </div>
        </section>

        <section class="contact-section">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 col-md-6">
                        <div class="contact-card">
                            <i class="bi bi-telephone-fill contact-icon"></i>
                            <h3>Teléfonos</h3>
                            <p>Llámanos para consultas y ventas:</p>
                            <div class="phone-list">
                                <a href="tel:+5492634419204" class="phone-link"><strong>Eduardo:</strong> 2634419204</a>
                                <a href="tel:+5492634959784" class="phone-link"><strong>Agustín:</strong> 2634959784</a>
                                <a href="tel:+5492634683895" class="phone-link"><strong>Claudio:</strong> 2634683895</a>
                                <a href="tel:+5492634526714" class="phone-link"><strong>Gastón:</strong> 2634526714</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <div class="contact-card">
                            <i class="bi bi-envelope-fill contact-icon"></i>
                            <h3>Correo Electrónico</h3>
                            <p>Para consultas generales y presupuestos, escríbenos a:</p>
                            <a href="mailto:tractoresclavijo23@gmail.com">tractoresclavijo23@gmail.com</a>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-12">
                        <div class="contact-card">
                            <i class="bi bi-share-fill contact-icon"></i>
                            <h3>Redes Sociales</h3>
                            <p>Síguenos para novedades y promociones:</p>
                            <ul>
                                <li class="mb-2"><a href="https://www.facebook.com/profile.php?id=100089567695602" target="_blank"><i class="bi bi-facebook me-2"></i>Facebook</a></li>
                                <li><a href="https://www.instagram.com/tractores.clavijo/" target="_blank"><i class="bi bi-instagram me-2"></i>Instagram</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="row mt-5 pt-4">
                    <div class="col-12">
                        <h2 class="text-center mb-4">Nuestra Ubicación</h2>
                        <div class="map-container">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3346.0432424922165!2d-68.6439776!3d-33.002635399999996!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x967e6de4a679c321%3A0x82df025e2966d93e!2sTractores%20Clavijo!5e0!3m2!1ses-419!2sar!4v1747683651990!5m2!1ses-419!2sar" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    </div>
                </div>

            </div>
        </section>
        <section class="contact-form-section">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8 col-md-10">
                        <h2 class="text-center">Envíanos un Mensaje</h2>
                        <form action="procesar_contacto.php" method="POST" class="contact-form needs-validation" novalidate>

                            <div class="mb-3">
                                <label for="contactName" class="form-label">Nombre Completo</label>
                                <input type="text" class="form-control" id="contactName" name="contactName" placeholder="Tu Nombre Completo" required>
                                <div class="invalid-feedback">
                                    Por favor, ingresa tu nombre.
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="contactEmail" class="form-label">Correo Electrónico</label>
                                <input type="email" class="form-control" id="contactEmail" name="contactEmail" placeholder="tuemail@ejemplo.com" required>
                                <div class="invalid-feedback">
                                    Por favor, ingresa un correo electrónico válido.
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="contactPhone" class="form-label">Teléfono (Opcional)</label>
                                <input type="tel" class="form-control" id="contactPhone" name="contactPhone" placeholder="Tu Número de Teléfono">
                            </div>

                            <div class="mb-3">
                                <label for="contactSubject" class="form-label">Asunto</label>
                                <input type="text" class="form-control" id="contactSubject" name="contactSubject" placeholder="Asunto de tu consulta" required>
                                <div class="invalid-feedback">
                                    Por favor, ingresa un asunto.
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="contactMessage" class="form-label">Mensaje</label>
                                <textarea class="form-control" id="contactMessage" name="contactMessage" rows="5" placeholder="Escribe tu mensaje aquí..." required></textarea>
                                <div class="invalid-feedback">
                                    Por favor, escribe tu mensaje.
                                </div>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-submit">Enviar Mensaje</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- FOOTER (Exactamente igual que en las otras páginas) -->
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
                        <li><a href="#"><i class="bi bi-caret-right-fill"></i> Repuestos</a></li>
                        <li><a href="#"><i class="bi bi-caret-right-fill"></i> Contacto</a></li>
                    </ul>
                </div>
            </div>
            <div class="text-center border-top pt-3 mt-4">
                <small>© <?php echo date("Y"); ?> Tractores Clavijo - Todos los derechos reservados</small>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>