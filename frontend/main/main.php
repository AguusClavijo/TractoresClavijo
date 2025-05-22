<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Tractores Clavijo - Inicio</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <link rel="stylesheet" href="../css/style.css" />

</head>

<body>
  <header>
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top navbar-custom">
      <div class="container">
        <a class="navbar-brand" href="#">Tractores Clavijo</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ms-auto align-items-center">
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="#">Inicio</a>
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

  <main>
    <div id="heroCarousel" class="carousel slide carousel-fade hero-carousel" data-bs-ride="carousel">
      <div class="carousel-indicators">
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="3" aria-label="Slide 4"></button>
      </div>
      <div class="carousel-inner">
        <div class="carousel-item active" data-bs-interval="5000">
          <img src="img/Tractor1.jpg" class="d-block w-100" alt="Tractor Verde Modelo RC 1004">
        </div>
        <div class="carousel-item" data-bs-interval="5000">
          <img src="img/Tractor2.jpg" class="d-block w-100" alt="Vista lateral Tractor Verde">
        </div>
        <div class="carousel-item" data-bs-interval="5000">
          <img src="img/Tractor3.jpg" class="d-block w-100" alt="Detalle Tractor Verde">
        </div>
        <div class="carousel-item" data-bs-interval="5000">
          <img src="img/Tractor4.jpg" class="d-block w-100" alt="Otro detalle Tractor Verde">
        </div>
      </div>
      <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Anterior</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Siguiente</span>
      </button>
    </div>

    <section class="container my-5 text-center">
      <h2>Nuestros Productos Destacados</h2>
      <p>Descubre la calidad y potencia de nuestros tractores.</p>
      <a href="../tractors/tractors.php" class="btn btn-success btn-lg" style="background-color: var(--light-green-accent); border-color: var(--light-green-accent);">Ver Tractores</a>
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
            <li><a href="#"><i class="bi bi-caret-right-fill"></i> Inicio</a></li>
            <li><a href="../about/about.php"><i class="bi bi-caret-right-fill"></i> Sobre Nosotros</a></li>
            <li><a href="../tractors/tractors.php"><i class="bi bi-caret-right-fill"></i> Tractores</a></li>
            <li><a href="../contact/contact.php"><i class="bi bi-caret-right-fill"></i> Contacto</a></li>
            <li><a href="../merch/merch.php"><i class="bi bi-caret-right-fill"></i> Merch</a></li> 
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